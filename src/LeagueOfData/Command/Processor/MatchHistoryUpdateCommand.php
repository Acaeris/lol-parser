<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Models\Json\JsonMatchHistories;
use LeagueOfData\Processor\MatchHistoryProcessor;

class MatchHistoryUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('update:matchhistory')
            ->setDescription('API processor command to get match history for a specific player')
            ->addArgument('playerId', InputArgument::REQUIRED, 'Player ID to process data for');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $log = $this->getContainer()->get('logger');
        $playerId = $input->getArgument('playerId');
        $source = new JsonMatchHistories($this->getContainer()->get('riot-api'));
        $matches = $source->findBySummoner($playerId);
        $processor = new MatchHistoryProcessor($log);

        foreach ($matches as $match) {
            $processor->process($match);
        }
    }
}
