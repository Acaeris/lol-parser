<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

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
     * @var string Select Query
     */
    private $select;

    /**
     * @var array Where parameters
     */
    private $where;

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
        $this->initServices();
        $this->buildRequest($input);

        $this->log->info('Checking Item data for update');

        if (count($this->database->fetch($this->select, $this->where)) == 0 || $input->getOption('force')) {
            $this->log->info("Update required");
            try {
                $this->database->add($this->service->fetch($this->where));
                $this->database->store();
            } catch (\Exception $ex) {
                $this->recover($input, 'Command failed to update data: ', $ex);
            }
            return;
        }

        $this->log->info('Skipping update for version '.$input->getArgument('release').' as data exists');
    }
    
    /**
     * Initialize used services
     */
    private function initServices()
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = $this->getContainer()->get('item-api');
        $this->database = $this->getContainer()->get('item-db');
        $this->messageQueue = $this->getContainer()->get('rabbitmq');
    }

    /**
     * Build a request object
     *
     * @param InputInterface $input
     */
    private function buildRequest(InputInterface $input)
    {
        $this->select = "SELECT * FROM items WHERE version = :version AND region = :region";
        $this->where = [
            'version' => $input->getArgument('release'),
            'region' => $input->getOption('region')
        ];

        if (null !== $input->getOption('itemId')) {
            $this->where['item_id'] = $this->getOption('itemId');
            $this->select .= " AND item_id = :item_id";
        }
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
}
