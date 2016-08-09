<?php

namespace LeagueOfData\Models\Sql;

use LeagueOfData\Models\History;

final class SqlHistory implements History {

    private $timestamp;
    private $type;
    private $data;

    public function __construct($data)
    {
        $this->timestamp = $data['timestamp'];
        $this->type = $data['type'];
        $this->data = $data['json'];
    }

    public function date()
    {
        return $this->timestamp;
    }

    public function type()
    {
        return $this->type;
    }

    public function data()
    {
        return $this->data;
    }
}
