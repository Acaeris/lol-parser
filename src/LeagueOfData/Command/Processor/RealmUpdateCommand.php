<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Adapters\Request\RealmRequest;

class RealmUpdateCommand extends ContainerAwareCommand
{
    private $log;
    private $database;

    /**
     * Configures the Realm update command
     */
    protected function configure()
    {
        $this->setName('update:realm')
            ->setDescription('API processor command for Data Dragon realm data');
    }

    /**
     * Executes the update, fetching all realms at the same time
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->service = $this->getContainer()->get('realm-api');
        $this->database = $this->getContainer()->get('realm-db');

        $this->log->info('Updating Realm data');
        $this->database->add($this->service->fetch(new RealmRequest([], '*')));
        $this->database->store();
    }
}
