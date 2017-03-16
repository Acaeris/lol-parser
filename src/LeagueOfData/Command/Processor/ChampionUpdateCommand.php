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
    private $log;
    private $service;
    private $database;
    private $messageQueue;

    protected function configure()
    {
        $this->setName('update:champion')
            ->setDescription('API processor command for champion data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addArgument('championId', InputArgument::OPTIONAL, 'Champion ID to process data for.'
                . ' (Will fetch all if not supplied)')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = $this->getContainer()->get('champion-api');
        $this->database = $this->getContainer()->get('champion-db');
        $this->messageQueue = $this->getContainer()->get('rabbitmq');

        if (count($this->database->findAll($input->getArgument('release'))) == 0 || $input->getOption('force')) {
            $this->updateData($input);
            return;
        }

        $this->log->info('Skipping update for version ' . $input->getArgument('release') . ' as data exists');
    }

    private function fetch(int $championId, string $version)
    {
        $this->log->info("Fetching champions for version: {$version}" . (isset($championId) ? " [{$championId}]" : ""));

        if (!empty($championId)) {
            $this->service->find($championId, $version);
            return;
        }

        $this->service->findAll($version);
    }

    private function recover(InputInterface $input, string $msg, \Exception $exception = null)
    {
        $params = '"command" : "update:champion",
                "release" : "' . $input->getArgument('release') . '"';

        if (!empty($input->getArgument('championId'))) {
            $params .= ', "championId" : "' . $input->getArgument('championId') . '"';
        }

        $this->messageQueue->addProcessToQueue('update:champion', "{ {$params} }");
        $this->log->error($msg, ['exception' => $exception]);
    }

    private function updateData(InputInterface $input)
    {
        try {
            $this->fetch($input->getArgument('championId'), $input->getArgument('release'));
            $this->log->info("Storing champion data for version " . $input->getArgument('release'));

            $this->database->addAll($this->service->transfer());
            $this->database->store();
        } catch (\Exception $exception) {
            $this->recover($input, 'Unexpected API response: ', $exception);
        } catch (ForeignKeyConstraintViolationException $exception) {
            preg_match("/CONSTRAINT `(\w+)`/", $exception, $matches);

            if ($matches[1] == 'version') {
                $this->log->info("Requesting refresh of version data");
                $this->messageQueue->addProcessToQueue('update:version',
                        '{ "command" : "update:version" }');
            }
        }
    }
}
