<?php

namespace LeagueOfData\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * RabbitMQ Service
 * @package LeagueOfData\Service
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
final class RabbitMQ
{
    /* @var Psr\Log\LoggerInterface Logger */
    private $log;

    /**
     * Set up RabbitMQ service
     *
     * @param LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Add a process request to the message queue
     *
     * @param string $command
     * @param string $params
     */
    public function addProcessToQueue(string $command, string $params)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('processing', false, false, false, false);
        $message = new AMQPMessage('{ "command" : "'.$command.'", "params" : '.$params.' }');
        $channel->basic_publish($message, '', 'processing');

        $channel->close();
        $connection->close();
    }
}
