<?php

namespace common\modules;

use common\models\Counter;
use common\models\User;
use common\models\UserCounter;
use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\rbac\Role;

class UserCounterModule {


    public User $user;
    public ?UserCounter $userCounter;
    /**@var $role Role[] * */
    private array $role = [];

    const ACTIVITY_TYPE_USER = 'userActivity';
    const ACTIVITY_TYPE_ROLE = 'roleActivity';
    const ACTIVITY_TYPE_PERMISSION = 'permissionActivity';

    const LIMIT = [
        self::ACTIVITY_TYPE_USER       => 3,
        self::ACTIVITY_TYPE_ROLE       => 5,
        self::ACTIVITY_TYPE_PERMISSION => 7
    ];

    const LIMIT_DESCRITION = [
        self::ACTIVITY_TYPE_USER       => 'Активность по пользователю',
        self::ACTIVITY_TYPE_ROLE       => 'Активность по роли',
        self::ACTIVITY_TYPE_PERMISSION => 'Активность по правам'
    ];

    /**
     * @throws Exception
     */
    public function __construct(User|ActiveRecord $user) {
        $this->user = $user;
        $this->userCounter = UserCounter::getByUser($user);
        if (!$this->userCounter instanceof UserCounter) throw new Exception("Error: User ID:$user->id does not have a counter");
        $this->role = Yii::$app->authManager->getRolesByUser($user->id);
    }

    /**
     * @param array $activityType Key is counter type [userActivity, roleActivity, permissionActivity],
     * value is how much to add or define (depending on the value of $set). Negative numbers are allowed.
     * @param bool $define If false(default) then += else =
     * @return bool
     */
    public function addAttempts(array $activityType, bool $define = false): bool {
        foreach ($activityType as $type => $count) {
            if (!array_key_exists($type, static::LIMIT)) return false;
            if (!$define) $this->userCounter->counter->$type->count += $count;
            else $this->userCounter->counter->$type->count = $count;
            $this->userCounter->counter->$type->time = time();
        }
        try {
            $this->userCounter->update();
        } catch (Exception) {
            return false;
        }
        return true;
    }

    /**
     * @param string $activityType Counter type [userActivity, roleActivity, permissionActivity]
     * @param int $count How much to add or define (depending on the value of $set). Negative numbers are allowed.
     * @param bool $define If false(default) then += else =
     * @return bool
     */
    public function addOneAttempt(string $activityType, int $count = 1, bool $define = false): bool {
        try {
            if (!array_key_exists($activityType, static::LIMIT)) return false;
            if (!$define) $this->userCounter->counter->$activityType->count += $count;
            else $this->userCounter->counter->$activityType->count = $count;
            $this->userCounter->counter->$activityType->time = time();
            $this->userCounter->update();
        } catch (Exception) {
            return false;
        }
        return true;
    }

    public function reset(): bool {
        return $this->addAttempts(array_map(fn($value): int => 0, static::LIMIT), true);
    }

    public function setAll(int $count): bool {
        return $this->addAttempts(array_map(fn($value): int => $count, static::LIMIT), true);
    }

    public function canUserDoThis(array $limits = []): bool {
        $counters = UserCounter::getCounterByUser($this->user);
        if (!$counters instanceof Counter) return false;
        if (empty($limits))
            return ($counters->userActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_USER] ||
                    $counters->roleActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_ROLE] ||
                    $counters->permissionActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_PERMISSION]) === false;
        else {
            foreach ($limits as $limit) {
                if ($counters->$limit->count >= static::LIMIT[$limit]) return false;
            }
        }
        return true;
    }

    public function canUserDoThisWithReason(): array {
        $reason = [];
        $counters = UserCounter::getCounterByUser($this->user);
        if (!$counters instanceof Counter) return ['error' => 'Can not get counter.'];
        if ($counters->userActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_USER])
            $reason[static::ACTIVITY_TYPE_USER] = "User limit exceeded: {$counters->userActivity->count}/" . static::LIMIT[static::ACTIVITY_TYPE_USER];
        if ($counters->roleActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_ROLE])
            $reason[static::ACTIVITY_TYPE_ROLE] = "Role limit exceeded: {$counters->roleActivity->count}/" . static::LIMIT[static::ACTIVITY_TYPE_ROLE];
        if ($counters->permissionActivity->count >= static::LIMIT[static::ACTIVITY_TYPE_PERMISSION])
            $reason[static::ACTIVITY_TYPE_PERMISSION] = "Permission limit exceeded: {$counters->permissionActivity->count}/" . static::LIMIT[static::ACTIVITY_TYPE_PERMISSION];
        return !empty($reason) ? $reason : ['No limits'];
    }

    public function getCurrentLimits(): array {
        $reason = [];
        $counters = UserCounter::getCounterByUser($this->user);
        $reason[static::ACTIVITY_TYPE_USER] = "User limit: {$counters->userActivity->count}/" . static::LIMIT[static::ACTIVITY_TYPE_USER];
        $reason[static::ACTIVITY_TYPE_ROLE] = "Role limit: {$counters->roleActivity->count}/" . static::LIMIT[static::ACTIVITY_TYPE_ROLE];
        $reason[static::ACTIVITY_TYPE_PERMISSION] = "Permission limit: {$counters->permissionActivity->count}/" . static::LIMIT[static::ACTIVITY_TYPE_PERMISSION];
        return $reason;
    }
}