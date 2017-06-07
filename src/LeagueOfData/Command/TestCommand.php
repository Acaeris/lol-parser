<?php

namespace LeagueOfData\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    /* @var LoggerInterface Logger */
    private $log;
    /* @var ChampionService DB Service */
    private $database;

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('test:champion')
            ->setDescription('Command for testing stuff')
            ->addArgument('championId', InputArgument::OPTIONAL, 'Champion ID to process data for.'
                . ' (Will fetch all if not supplied)');
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
        $this->database = $this->getContainer()->get('champion-db');

        var_dump($this->database->test());
    }
}