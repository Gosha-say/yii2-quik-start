<?php

namespace console\controllers;

use common\models\SignupForm;
use common\models\User;
use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class StartUpController extends Controller {

    private string $seperator = '';
    const STARTUP_USERS = [
        [
            'username' => 'admin',
            'email'    => 'admin@yii.local',
            'password' => 'EmptyPassword',
        ],
        [
            'username' => 'user',
            'email'    => 'user@yii.local',
            'password' => 'EmptyPassword',
        ],
    ];

    /**
     * @throws Exception
     */
    public function actionIndex(): void {
        $model = new SignupForm();
        Yii::$app->db->createCommand()->truncateTable('user')->execute();
        Console::stdout(PHP_EOL . str_pad('Table ' . User::tableName() .
                ' truncated' . PHP_EOL, 16 + strlen(User::tableName()) + 30, '-') . PHP_EOL);
        foreach (self::STARTUP_USERS as $user) {
            if ($model->load(['SignupForm' => $user]) && $model->signup(false)) {
                $newUser = User::findOne(['username' => $user['username']]);
                $newUser->status = User::STATUS_ACTIVE;
                if (!$newUser->save()) throw new Exception("Can not add new user {$user['username']}");
                self::printUsers($user);
            }
        }
    }

    private static function printUsers(array $user): void {
        $output = ("New user added" . PHP_EOL .
            "Name: {$user['username']}" . PHP_EOL .
            "Password: {$user['password']}" . PHP_EOL .
            "Email: {$user['email']}" . PHP_EOL
        );
        $_output = str_pad($output, strlen($output) + 30, "-");
        Console::stdout($_output . PHP_EOL);
    }
}