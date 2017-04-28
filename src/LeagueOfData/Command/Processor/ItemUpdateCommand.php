<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ItemUpdateCommand extends ContainerAwareCommand
{
    /* @var LoggerInterface Logger */
    private $log;
    /* @var ItemService API Service */
    private $service;
    /* @var ItemService DB Service */
    private $database;
    /* @var object Messsage Queue Service */
    private $messageQueue;

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('update:item')
            ->setDescription('API processor command for item data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addArgument('itemId', InputArgument::OPTIONAL, 'Item ID to process data for.'
                . ' (Will fetch all if not supplied)')
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

        if (count($this->database->fetch($input->getArgument('release'), $input->getArgument('itemId'))) == 0
            || $input->getOption('force')) {
            $this->log->notice("Update required");
            $this->updateData($input);
            return;
        }

        $this->log->info('Skipping update for version '.$input->getArgument('release').' as data exists');
    }

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

    private function updateData(InputInterface $input)
    {
        try {
            $this->service->fetch($input->getArgument('release'), $input->getArgument('itemId'));
            $this->log->info("Storing item data for version ".$input->getArgument('release'));

            $this->database->add($this->service->transfer());
            $this->database->store();
        } catch (\Exception $exception) {
            $this->recover($input, 'Unexpected API response: ', $exception);
        } catch (ForeignKeyConstraintViolationException $exception) {
            preg_match("/CONSTRAINT `(\w+)`/", $exception, $matches);

            if ($matches[1] == 'version') {
                $this->log->info("Requesting refresh of version data");
                $this->messageQueue->addProcessToQueue(
                    'update:version',
                    '{ "command" : "update:version" }'
                );
            }
        }
    }
}
