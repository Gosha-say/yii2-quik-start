<?php

namespace common\models;

use JsonSerializable;

class Counter implements JsonSerializable {

    public CounterRoot $userActivity;
    public CounterRoot $roleActivity;
    public CounterRoot $permissionActivity;

    public static function makeCounter(UserCounter $counter): Counter {
        $model = new static();
        $model->userActivity = new CounterRoot($counter['counter']['userActivity']['time'], $counter['counter']['userActivity']['count']);
        $model->roleActivity = new CounterRoot($counter['counter']['roleActivity']['time'], $counter['counter']['roleActivity']['count']);
        $model->permissionActivity = new CounterRoot($counter['counter']['permissionActivity']['time'], $counter['counter']['permissionActivity']['count']);
        return $model;
    }

    public static function makeEmpty(): Counter {
        $model = new static();
        $model->userActivity = new CounterRoot(time(), [new CounterType(time(), 'user', 0)]);
        $model->roleActivity = new CounterRoot(time(), [new CounterType(time(), 'user', 0)]);
        $model->permissionActivity = new CounterRoot(time(), [new CounterType(time(), 'user', 0)]);
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): string {
        return json_encode((array)$this, JSON_UNESCAPED_SLASHES);
    }
}