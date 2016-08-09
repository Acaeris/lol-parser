<?php

namespace LeagueOfData\Processor;

use LeagueOfData\Processor\ProcessorInterface;
use LeagueOfData\Models\MatchHistory;
use Psr\Log\LoggerInterface;

class MatchHistoryProcessor implements ProcessorInterface
{
    private $log;

    public function __construct(LoggerInterface $log) {
        $this->log = $log;
    }

    public function process($match) {
        if (!$match instanceof MatchHistory) {
            $this->log->error('Incorrect object passed to process:matchhistory');
        }
        $this->log->info('<info>Processing from Match History:</> ' . $match->id());
    }
}
