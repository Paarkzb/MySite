<?php
/* @var $user app\models\User */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

Привет <?= $user->username ?>
Перейди по ссылке ниже для восстановления пароля

<?= $resetLink ?>
