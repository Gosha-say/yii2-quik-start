<?php

namespace common\models;

use JsonSerializable;

class Counter implements JsonSerializable {

    public CounterType $userActivity;
    public CounterType $roleActivity;
    public CounterType $permissionActivity;

    public static function makeCounter(UserCounter $counter): Counter {
        $model = new static();
        $model->userActivity = new CounterType($counter['counter']['userActivity']['time'], $counter['counter']['userActivity']['count']);
        $model->roleActivity = new CounterType($counter['counter']['roleActivity']['time'], $counter['counter']['roleActivity']['count']);
        $model->permissionActivity = new CounterType($counter['counter']['permissionActivity']['time'], $counter['counter']['permissionActivity']['count']);
        return $model;
    }

    public static function makeEmpty(): Counter {
        $model = new static();
        $model->userActivity = $model->roleActivity = $model->permissionActivity = new CounterType(time(), 0);
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string {
        return json_encode((array)$this, JSON_UNESCAPED_SLASHES);
    }
}