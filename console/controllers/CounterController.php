<?php

namespace console\controllers;

use common\models\Counter;
use common\models\User;
use common\models\UserCounter;
use common\modules\UserCounterModule;
use Yii;
use yii\console\Controller;
use yii\helpers\VarDumper;

class CounterController extends Controller {

    public function actionTest() {
        Yii::$app->db->createCommand()->truncateTable("user_counter")->execute();
        $cnt = new UserCounter(['user_id' => 20, 'counter' => Counter::makeEmpty()]);
        $cnt->save();
        $test = UserCounter::findOne(['user_id' => 20]);
        VarDumper::dump($test);
    }

    public function actionGet() {
        $user = User::find()->where(['id' => 1])->one();
        //$counter = UserCounter::findOne(['user_id' => 10]);
        //VarDumper::dump($counter);
        $module = new UserCounterModule($user);
        VarDumper::dump($module->canUserDoThisWithReason());
    }
}