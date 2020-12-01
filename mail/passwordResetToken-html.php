<?php

/* @var $user app\models\User */
use yii\helpers\Html;

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    <p>Привет <?= Html::encode($user->username) ?>,</p>
    <p>Перейди по ссылке ниже для восстановления пароля:</p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
