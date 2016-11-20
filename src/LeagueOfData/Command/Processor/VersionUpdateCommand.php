<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use LeagueOfData\Models\Version;

class VersionUpdateCommand extends ContainerAwareCommand
{
    private $db;
    private $log;
    private $service;
    private $mq;

    protected function configure()
    {
        $this->setName('update:version')
            ->setDescription('API processor command for version data')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = $this->getContainer()->get('version-api');
        $this->db = $this->getContainer()->get('version-db');
        $this->mq = $this->getContainer()->get('rabbitmq');

        $this->log->info('Fetching version data');
        $versions = $this->service->findAll();

        $this->db->addAll($this->service->transfer());
        $this->db->store();

        foreach ($versions as $version) {
            $this->queueUpdates($version, $input->getOption('force'));
        }
    }

    private function queueUpdates(Version $version, $force)
    {
        $this->log->info("Queuing update for version " . $version->fullVersion());
        $this->mq->addProcessToQueue('update:champion', '{
            "command" : "update:champion",
            "release" : "' . $version->fullVersion() . '",
            "--force" : "' . $force . '"
        }');
        $this->mq->addProcessToQueue('update:item', '{
            "command" : "update:item",
            "release" : "' . $version->fullVersion() . '",
            "force" : "' . $force . '"
        }');
    }
}
