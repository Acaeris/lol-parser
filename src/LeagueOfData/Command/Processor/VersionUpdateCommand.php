<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use LeagueOfData\Adapters\RequestInterface;
use LeagueOfData\Adapters\Request\VersionRequest;

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

        $this->log->notice('Checking version data for update');
        $request = $this->buildRequest($input);

        if (count($this->database->fetch($request)) == 0 || $input->getOption('force')) {
            $this->log->notice("Update required");
            $this->updateData($input);
            return;
        }

        $this->log->info("Skipping update");
    }

    /**
     * Build a request object
     *
     * @param InputInterface $input
     * @return RequestInterface
     */
    private function buildRequest(InputInterface $input) : RequestInterface
    {
        return new VersionRequest([], '*');
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
            $this->log->info("Storing version data");

            $this->database->add($this->service->transfer());
            $this->database->store();
            $this->queueUpdates($input->getOption('force'));
        } catch (\Exception $exception) {
            $this->recover($input, "Unexpected API response: ", $exception);
        }
    }

    /**
     * Queue new updates
     *
     * @param bool $force Force update of this version
     */
    private function queueUpdates(bool $force)
    {
        foreach ($this->service->transfer() as $version) {
            $this->log->info("Queuing update for version ".$version->getFullVersion());
            $this->messageQueue->addProcessToQueue('update:champion', '{
                "command" : "update:champion",
                "release" : "'.$version->getFullVersion().'",
                "--force" : "'.$force.'"
            }');
            $this->messageQueue->addProcessToQueue('update:item', '{
                "command" : "update:item",
                "release" : "'.$version->getFullVersion().'",
                "--force" : "'.$force.'"
            }');
        }
    }
}
