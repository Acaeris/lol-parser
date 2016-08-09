<?php

namespace LeagueOfData\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

final class RabbitMQ {

    private $log;

    public function __construct(LoggerInterface $log) {
        $this->log = $log;
    }

    public function addProcessToQueue($command, $params)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('processing', false, false, false, false);
        $message = new AMQPMessage('{ "command" : "' . $command .'", "params" : ' . $params . ' }');
        $channel->basic_publish($message, '', 'processing');

        $channel->close();
        $connection->close();
    }
}
