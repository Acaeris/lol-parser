<?php

namespace LeagueOfData\Helpers\Format;

class Season
{
    private $_seasons = [
        'PRESEASON3' => 'Pre-season 3 (2013)',
        'SEASON3' => '3 (2013)',
        'PRESEASON2014' => 'Pre-season 2014',
        'SEASON2014' => 2014,
        'PRESEASON2015' => 'Pre-season 2015',
        'SEASON2015' => 2015,
        'PRESEASON2016' => 'Pre-season 2016',
        'SEASON2016' => 2016
    ];

    public function format($season)
    {
        if (isset($this->_seasons[$season])) {
            return $this->_seasons[$season];
        }
        return $season;
    }
}
