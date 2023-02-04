<?php

/** @var yii\web\View $this */

use common\models\User;
use common\modules\UserCounterModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
/**@var $user User */
$user = Yii::$app->user->identity;
$counter = !Yii::$app->user->isGuest ? new UserCounterModule($user) : null;
?>
<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Hello!</h1>
            <p class="fs-5 fw-light">You have successfully created Yii-powered application.</p>
        </div>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Buttons:</h2>
                <?php if ($user instanceof User && !$user->locked) { ?>
                    <a class="btn btn-success">Locked button (unlocked by any limit)</a>
                <?php } else { ?>
                    <span class="btn btn-dark locked-pointer">Locked button (locked by any limit)</span>
                <?php } ?>
                <br><br>
                <?php if ($counter->canUserDoThis([UserCounterModule::ACTIVITY_TYPE_USER])) { ?>
                    <a class="btn btn-success">Locked button (unlocked by <?=UserCounterModule::ACTIVITY_TYPE_USER ?> limit)</a>
                <?php } else { ?>
                    <span class="btn btn-dark locked-pointer">Locked button (locked by <?=UserCounterModule::ACTIVITY_TYPE_USER ?> limit)</span>
                <?php } ?>
            </div>
            <div class="col-lg-4">
                <h2>User status:</h2>
                <?php
                if (!Yii::$app->user->isGuest) {
                    echo implode('<br>', $counter->getCurrentLimits());
                    echo '<hr><p class="text-white font-weight-bold text-bg-danger">';
                    echo implode('<br>', $counter->canUserDoThisWithReason());
                    echo '</p>';
                } else echo 'Unauthorised';
                ?>
            </div>
            <div class="col-lg-4">
                <h2>Functions:</h2>
                <?php
                if (Yii::$app->user->isGuest)
                    echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => ['btn btn-link login text-decoration-none']]), ['class' => ['d-flex']]);
                else { ?>
                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => '/site/limits']); ?>
                    <?php echo Html::submitButton('Reset counters to 0', ['name' => 'reset', 'value' => 0, 'class' => 'btn btn-success']); ?> <br><br>
                    <?php echo Html::submitButton('Set all counters to over limit', ['name' => 'reset', 'value' => 99, 'class' => 'btn btn-success']); ?><br><br>
                    <?php echo Html::submitButton('Set ' . UserCounterModule::ACTIVITY_TYPE_USER . ' to over limit', ['name' => UserCounterModule::ACTIVITY_TYPE_USER, 'value' => 99, 'class' => 'btn btn-success']); ?><br><br>
                    <?php echo Html::submitButton('Set ' . UserCounterModule::ACTIVITY_TYPE_ROLE . ' to over limit', ['name' => UserCounterModule::ACTIVITY_TYPE_ROLE, 'value' => 99, 'class' => 'btn btn-success']); ?><br><br>
                    <?php echo Html::submitButton('Set ' . UserCounterModule::ACTIVITY_TYPE_PERMISSION . ' to over limit', ['name' => UserCounterModule::ACTIVITY_TYPE_PERMISSION, 'value' => 99, 'class' => 'btn btn-success']); ?><br><br>
                    <?php echo Html::submitButton('Set ' . UserCounterModule::ACTIVITY_TYPE_USER . ' to limit  -1', ['name' => UserCounterModule::ACTIVITY_TYPE_USER, 'value' => UserCounterModule::LIMIT[UserCounterModule::ACTIVITY_TYPE_USER] - 1, 'class' => 'btn btn-success']); ?>
                    <br><br>
                    <?php echo Html::submitButton('Set ' . UserCounterModule::ACTIVITY_TYPE_ROLE . ' to limit  -1', ['name' => UserCounterModule::ACTIVITY_TYPE_ROLE, 'value' => UserCounterModule::LIMIT[UserCounterModule::ACTIVITY_TYPE_ROLE] - 1, 'class' => 'btn btn-success']); ?>
                    <br><br>
                    <?php echo Html::submitButton('Set ' . UserCounterModule::ACTIVITY_TYPE_PERMISSION . ' to limit  -1', ['name' => UserCounterModule::ACTIVITY_TYPE_PERMISSION, 'value' => UserCounterModule::LIMIT[UserCounterModule::ACTIVITY_TYPE_PERMISSION] - 1, 'class' => 'btn btn-success']); ?>
                    <br><br>
                    <?php ActiveForm::end() ?>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
