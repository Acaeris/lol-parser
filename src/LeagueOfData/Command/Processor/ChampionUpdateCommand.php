<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class ChampionUpdateCommand extends ContainerAwareCommand
{
    /* @var LoggerInterface Logger */
    private $log;
    /* @var ChampionService API Service */
    private $service;
    /* @var ChampionService DB Service */
    private $database;
    /* @var object Messsage Queue Service */
    private $messageQueue;

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('update:champion')
            ->setDescription('API processor command for champion data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addArgument('championId', InputArgument::OPTIONAL, 'Champion ID to process data for.'
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
        $this->service = $this->getContainer()->get('champion-api');
        $this->database = $this->getContainer()->get('champion-db');
        $this->messageQueue = $this->getContainer()->get('rabbitmq');

        $this->log->notice('Checking Champion data for update');

        if (count($this->database->fetch($input->getArgument('release'), $input->getArgument('championId'))) == 0
            || $input->getOption('force')) {
            $this->log->notice("Update required");
            $this->updateData($input);
            return;
        }

        $this->log->info('Skipping update for version '.$input->getArgument('release').' as data exists');
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
        $params = '"command" : "update:champion",
                "release" : "'.$input->getArgument('release').'"';

        if (!empty($input->getArgument('championId'))) {
            $params .= ', "championId" : "'.$input->getArgument('championId').'"';
        }

        $this->messageQueue->addProcessToQueue('update:champion', "{ {$params} }");
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
            $this->service->fetch($input->getArgument('release'), $input->getArgument('championId'));
            $this->log->info("Storing champion data for version ".$input->getArgument('release'));

            $this->database->addAll($this->service->transfer());
            $this->database->store();
        } catch (\Exception $exception) {
            $this->recover($input, 'Unexpected API response: ', $exception);
        } catch (ForeignKeyConstraintViolationException $exception) {
            preg_match("/CONSTRAINT `(\w+)`/", $exception, $matches);

            if ($matches[1] == 'version') {
                $this->log->notice("Version data outdated. Automating update.");
                $this->messageQueue->addProcessToQueue(
                    'update:version',
                    '{ "command" : "update:version" }'
                );
            }
        }
    }
}
