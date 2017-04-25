<?php

namespace LeagueOfData\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Input\ArrayInput;

final class QueueCommand extends ContainerAwareCommand
{
    private $log;
    private $output;

    protected function configure()
    {
        $this->setName('queue:watch')
            ->setDescription('Processing queue watcher.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->log = $this->getContainer()->get('logger');
        $this->output = $output;
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('processing', false, false, false, false);
        $channel->basic_consume('processing', '', false, true, false, false, array($this, 'process'));

        while (count($channel->callbacks)) {
            $this->log->info("Watching Queue... To exit press CTRL + C");
            $channel->wait();
        }
    }

    public function process(AMQPMessage $msg)
    {
        $task = json_decode($msg->body, true);
        $command = $this->getApplication()->find($task['command']);
        $commandInput = new ArrayInput($task['params']);
        $command->run($commandInput, $this->output);
        sleep(1);
    }
}
