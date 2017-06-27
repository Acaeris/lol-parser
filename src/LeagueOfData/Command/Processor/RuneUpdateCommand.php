<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuneUpdateCommand extends ContainerAwareCommand
{

    /**
     * @var LoggerInterface Logger
     */
    private $log;

    /**
     * @var FetchServiceInterface API Service
     */
    private $service;

    /**
     * @var StoreServiceInterface DB Service
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
        $this->setName('update:rune')
            ->setDescription('API processor command for rune data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for.')
            ->addOption('runeId', 'i', InputOption::VALUE_REQUIRED, 'Rune ID to process data for.'
                .' (Wull fetch all if not supplied)')
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', 'euw')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data', false);
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->initServices();
        $this->buildRequest($input);

        $this->log->info('Checking Rune data for update');

        if (count($this->database->fetch($this->select, $this->where)) == 0 || $input->getOption('force')) {
            $this->log->info("Update required");
            try {
                $this->database->clear();
                $this->database->add($this->service->fetch($this->where));
                $this->database->store();
            } catch (\Exception $exception) {
                $this->recover($input, 'Command failed to update data: ', $exception);
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
        $this->service = $this->getContainer()->get('rune-api');
        $this->database = $this->getContainer()->get('rune-db');
        $this->messageQueue = $this->getContainer()->get('rabbitmq');
    }

    /**
     * Build a request object
     *
     * @param InputInterface $input
     */
    private function buildRequest(InputInterface $input)
    {
        $this->select = "SELECT * FROM runes WHERE version = :version AND region = :region";
        $this->where = [
            'version' => $input->getArgument('release'),
            'region' => $input->getOption('region')
        ];

        if (null !== $input->getOption('runeId')) {
            $this->where['rune_id'] = $this->getOption('runeId');
            $this->select .= " AND rune_id = :rune_id";
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
        $params = '"command": "update:rune", "release": "'.$input->getArgument('release').'"';

        if (!empty($input->getOption('runeId'))) {
            $params .= ', "runeId": "'.$input->getArgument('runeId').'"';
        }

        $this->messageQueue->addProcessToQueue('update:rune', "{ {$params} }");
        $this->log->error($msg, ['exception' => $exception]);
    }
}
