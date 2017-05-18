<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\ItemRequest;

class ItemUpdateCommand extends ContainerAwareCommand
{
    /**
     * @var LoggerInterface Logger
     */
    private $log;
    /**
     * @var ItemService API Service
     */
    private $service;
    /**
     * @var ItemService DB Service
     */
    private $database;
    /**
     * @var object Messsage Queue Service
     */
    private $messageQueue;

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('update:item')
            ->setDescription('API processor command for item data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addOption('itemId', 'i', InputOption::VALUE_REQUIRED, 'Item ID to process data for.'
                . ' (Will fetch all if not supplied)')
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', "euw")
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = $this->getContainer()->get('item-api');
        $this->database = $this->getContainer()->get('item-db');
        $this->messageQueue = $this->getContainer()->get('rabbitmq');

        $this->log->notice('Checking Item data for update');
        $request = $this->buildRequest($input);

        if (count($this->database->fetch($request)) == 0 || $input->getOption('force')) {
            $this->log->notice("Update required");
            $this->updateData($input);
            return;
        }

        $this->log->info('Skipping update for version '.$input->getArgument('release').' as data exists');
    }

    /**
     * Build a request object
     *
     * @param InputInterface $input
     * @return RequestInterface
     */
    private function buildRequest(InputInterface $input) : RequestInterface
    {
        $where = [
            'version' => $input->getArgument('release'),
            'region' => $input->getOption('region')
        ];

        if (null !== $input->getOption('itemId')) {
            $where['item_id'] = $this->getOption('itemId');
        }

        return new ItemRequest($where, '*');
    }

    /**
     * If the update process fails in a recoverable state,
     * will record the error and return the request to the message queue.
     *
     * @param InputInterface $input
     * @param string $msg
     * @param \Exception $exception
     */
    private function recover(InputInterface $input, string $msg, \Exception $exception = null)
    {
        $params = '"command" : "update:item",
            "release" : "'.$input->getArgument('release').'"';

        if (!empty($input->getArgument('itemId'))) {
            $params .= ', "itemId" : "'.$input->getArgument('itemId').'"';
        }

        $this->messageQueue->addProcessToQueue('update:item', "{ {$params} }");
        $this->log->error($msg, ['exception' => $exception]);
    }

    /**
     * Process data and update DB
     *
     * @param InputInterface $input
     */
    private function updateData(InputInterface $input)
    {
        try {
            $this->service->fetch($this->buildRequest($input));
            $this->log->info("Storing item data");

            $this->database->add($this->service->transfer());
            $this->database->store();
        } catch (\Exception $exception) {
            $this->recover($input, 'Unexpected API response: ', $exception);
        }
    }
}
