<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RealmUpdateCommand extends ContainerAwareCommand
{
    private $log;
    private $db;

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
        $this->db = $this->getContainer()->get('realm-db');

        $this->log->info('Updating Realm data');
        $this->db->addAll($this->service->findAll());
        $this->db->store();
    }
}
