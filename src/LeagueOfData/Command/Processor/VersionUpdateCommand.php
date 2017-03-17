<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use LeagueOfData\Models\Version;

class VersionUpdateCommand extends ContainerAwareCommand
{
    private $database;
    private $log;
    private $service;
    private $messageQueue;

    /**
     * Configure Command
     */
    protected function configure()
    {
        $this->setName('update:version')
            ->setDescription('API processor command for version data')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data.', false);
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input Input data
     * @param OutputInterface $output Output data
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = $this->getContainer()->get('version-api');
        $this->database = $this->getContainer()->get('version-db');
        $this->messageQueue = $this->getContainer()->get('rabbitmq');

        $this->log->info('Fetching version data');
        $versions = $this->service->findAll();

        $this->database->addAll($this->service->transfer());
        $this->database->store();

        foreach ($versions as $version) {
            $this->queueUpdates($version, $input->getOption('force'));
        }
    }

    /**
     * Queue new updates
     *
     * @param Version $version Version to update for
     * @param bool $force Force update of this version
     */
    private function queueUpdates(Version $version, bool $force)
    {
        $this->log->info("Queuing update for version ".$version->fullVersion());
        $this->messageQueue->addProcessToQueue('update:champion', '{
            "command" : "update:champion",
            "release" : "'.$version->fullVersion().'",
            "--force" : "'.$force.'"
        }');
        $this->messageQueue->addProcessToQueue('update:item', '{
            "command" : "update:item",
            "release" : "'.$version->fullVersion().'",
            "--force" : "'.$force.'"
        }');
    }
}
