<?php

namespace common\models;

class CounterType {
    public int $time;
    public string $type;
    public int $count;

    /**
     * @param int $time
     * @param string $type
     * @param int $count
     */
    public function __construct(int $time, string $type, int $count) {
        $this->time = $time;
        $this->type = $type;
        $this->count = $count;
    }
}
