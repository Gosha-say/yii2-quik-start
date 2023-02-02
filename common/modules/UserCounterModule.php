<?php

namespace common\modules;

use common\models\Counter;
use common\models\User;
use common\models\UserCounter;
use Yii;
use yii\db\ActiveRecord;
use yii\rbac\Role;

class UserCounterModule {


    public User $user;
    /**@var $role Role[] * */
    //private array $role = [];

    const ACTIVITY_TYPE_USER = 'userActivity';
    const ACTIVITY_TYPE_ROLE = 'roleActivity';
    const ACTIVITY_TYPE_PERMISSION = 'permissionActivity';

    const LIMIT = [
        self::ACTIVITY_TYPE_USER       => 3,
        self::ACTIVITY_TYPE_ROLE       => 5,
        self::ACTIVITY_TYPE_PERMISSION => 7
    ];

    public function __construct(User|ActiveRecord $user) {
        $this->user = $user;
        $this->role = Yii::$app->authManager->getRolesByUser($user->id);
    }

    public function canUserDoThis(): bool {
        $counters = UserCounter::getByUser($this->user);
        if (!$counters instanceof Counter) return false;
        return ($counters->userActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_USER] ||
                $counters->roleActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_ROLE] ||
                $counters->permissionActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_PERMISSION]) === false;
    }

    public function canUserDoThisWithReason(): array {
        $reason = [];
        $counters = UserCounter::getByUser($this->user);
        if (!$counters instanceof Counter) return ['error' => 'Can not get counter.'];
        if ($counters->userActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_USER]) $reason[static::ACTIVITY_TYPE_USER] = 'User limit exceeded';
        if ($counters->roleActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_ROLE]) $reason[static::ACTIVITY_TYPE_ROLE] = 'Role limit exceeded';
        if ($counters->permissionActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_PERMISSION]) $reason[static::ACTIVITY_TYPE_PERMISSION] = 'Permission limit exceeded';
        return $reason;
    }
}