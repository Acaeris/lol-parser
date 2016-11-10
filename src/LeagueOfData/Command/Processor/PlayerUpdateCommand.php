<?php

namespace LeagueOfData\Command\Processor;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LeagueOfData\Models\Json\JsonSummoners;
use LeagueOfData\Processor\PlayerProcessor;

class PlayerUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('update:player')
            ->setDescription('API processor command for player data')
            ->addArgument('playerIds', InputArgument::IS_ARRAY, 'List of player IDs to process data for');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $log = $this->getContainer()->get('logger');
        $playerIds = $input->getArgument('playerIds');
        $source = new JsonSummoners($this->getContainer()->get('riot-api'));
        $players = $source->find($playerIds);
        $processor = new PlayerProcessor($log);

        foreach ($players as $player) {
            $processor->process($player);
        }
    }
}
