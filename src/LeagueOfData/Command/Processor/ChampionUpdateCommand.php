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
    private $db;
    private $data = [];

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
        $this->db = $this->getContainer()->get('champion-db');

        if (count($this->db->findAll($input->getArgument('release'))) == 0 || $input->getOption('force')) {
            $this->updateData($input);
        } else {
            $this->log->info('Skipping update for version ' . $input->getArgument('release') . ' as data exists');
        }
    }

    private function fetch($championId, $version)
    {
        $this->log->info("Fetching champions for version: {$version}" . (isset($championId) ? " [{$championId}]" : ""));
        if (!empty($championId)) {
            $this->data = $this->service->find($championId, $version);
        } else {
            $this->data = $this->service->findAll($version);
        }
    }

    private function recover(InputInterface $input, $msg, \Exception $e = null)
    {
        $params = '"command" : "update:champion",
                "release" : "' . $input->getArgument('release') . '"';
        if (!empty($input->getArgument('championId'))) {
            $params .= ', "championId" : "' . $input->getArgument('championId') . '"';
        }
        $mq = $this->getContainer()->get('rabbitmq');
        $mq->addProcessToQueue('update:champion', "{ {$params} }");
        $this->log->error($msg . ['exception' => $e]);
    }

    private function updateData(InputInterface $input)
    {
        $this->service = $this->getContainer()->get('champion-api');

        try {
            $this->fetch($input->getArgument('championId'), $input->getArgument('release'));
            $this->log->info("Storing champion data for version " . $input->getArgument('release'));

            foreach ($this->data as $champion) {
                $this->db->add($champion);
            }
            $this->db->store();
        } catch (\Exception $e) {
            $this->recover($input, 'Unexpected API response: ', $e);
        } catch (ForeignKeyConstraintViolationException $e) {
            preg_match("/CONSTRAINT `(\w+)`/", $e, $matches);

            if ($matches[1] == 'version') {
                $this->log->info("Requesting refresh of version data");
                $mq = $this->getContainer()->get('rabbitmq');
                $mq->addProcessToQueue('update:version', '{ "command" : "update:version" }');
            }
        }
    }
}
