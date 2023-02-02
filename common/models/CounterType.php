<?php

namespace common\models;

class CounterType {
    public int $time;
    public int $count;

    /**
     * @param int $time
     * @param int $count
     */
    public function __construct(int $time, int $count) {
        $this->time = $time;
        $this->count = $count;
    }
}