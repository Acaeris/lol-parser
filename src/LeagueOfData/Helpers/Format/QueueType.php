<?php

namespace LeagueOfData\Helpers\Format;

class QueueType
{
    private $_queues = [
        'TEAM_BUILDER_DRAFT_RANKED_5x5' => 'Dynamic Queue Ranked Draft',
        'RANKED_SOLO_5x5' => 'Solo Queue Ranked Draft',
        'RANKED_TEAM_3x3' => 'Team Ranked Draft (TT)',
        'RANKED_TEAM_5x5' => 'Team Ranked Draft'
    ];

    public function format($type)
    {
        if (isset($this->_queues[$type])) {
            return $this->_queues[$type];
        }
        return $type;
    }
}
