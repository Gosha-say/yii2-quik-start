<?php

namespace common\models;

class CounterRoot {
    public int $time;
    public array $count;

    /**
     * @param int $time
     * @param CounterType[] $count
     */
    public function __construct(int $time, array $count) {
        $this->time = $time;
        $this->count = $count;
    }
}