<?php
namespace LeagueOfData\Service;

/**
 * Message Queue Service Interface
 *
 * @package LeagueOfData\Service
 * @author  Caitlyn Osborne <acaeris@gmail.com>
 * @link    http://lod.gg League of Data
 */
interface MessageQueueInterface
{
    /**
     * Add a process to the queue
     */
    public function addProcessToQueue(string $command, string $params);
}
