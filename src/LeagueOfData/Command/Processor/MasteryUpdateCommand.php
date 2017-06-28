<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MasteryUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('update:mastery')
            ->setDescription('API processor command for mastery data')
            ->addArgument('release', InputArgument::REQUIRED, 'Version number to process data for')
            ->addOption('masteryId', 'i', InputOption::VALUE_REQUIRED, 'Mastery ID to process data for.'
                .' (Will fetch all if not supplied)')
            ->addOption('region', 'r', InputOption::VALUE_REQUIRED, 'Region to fetch data for. (Default "euw")', 'euw')
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'Force a refresh of the data', false);
    }
}
