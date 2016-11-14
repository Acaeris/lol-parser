<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Models\Json\JsonVersions;

class VersionUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('update:version')
            ->setDescription('API processor command for version data');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $log = $this->getContainer()->get('logger');
        $service = new JsonVersions($this->getContainer()->get('riot-api'));

        $log->info('Fetching version data');
        $versions = $service->collectAll();

        $mq = $this->getContainer()->get('rabbitmq');

        foreach ($versions as $version) {
            $version->store($this->getContainer()->get('sql-adapter'));
            $log->info("Queuing update for version " . $version->versionNumber());
            $mq->addProcessToQueue('update:champion', '{
                "command" : "update:champion",
                "release" : "' . $version->versionNumber() . '"
            }');
            $mq->addProcessToQueue('update:item', '{
                "command" : "update:item",
                "release" : "' . $version->versionNumber() . '"
            }');
        }
    }
}
