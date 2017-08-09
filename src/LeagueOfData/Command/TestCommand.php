<?php

namespace LeagueOfData\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    /**
     * Configure the command
     */
    protected function configure()
    {
        $this->setName('test:champion')
            ->setDescription('Command for testing stuff');
    }

    /**
     * Execute the command
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $producer = $this->getContainer()->get('old_sound_rabbit_mq.champion_updates_producer');
        $producer->publish(serialize(['force' => true, 'version' => '7.9.1']));
    }
}
