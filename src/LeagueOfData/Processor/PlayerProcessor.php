<?php

namespace LeagueOfData\Processor;

use LeagueOfData\Processor\ProcessorInterface;
use LeagueOfData\Models\Summoner;
use Psr\Log\LoggerInterface;

class PlayerProcessor implements ProcessorInterface
{
    private $log;
    
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }
    
    public function process($player)
    {
        if (!$player instanceof Summoner) {
            $this->log->error('Incorrect object passed to process:player');
        }
        $this->log->info('<info>Processing Player:</> '.$player->name());
    }
}
