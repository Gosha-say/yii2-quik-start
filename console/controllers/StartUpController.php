<?php

namespace console\controllers;

use common\models\SignupForm;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class StartUpController extends Controller {

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

    public function actionIndex(): void {
        $model = new SignupForm();
        foreach (self::STARTUP_USERS as $user) {
            if ($model->load(['SignupForm' => $user]) && $model->signup(false)) {
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
        Console::stdout(str_pad($output, 20) . PHP_EOL);
    }
}