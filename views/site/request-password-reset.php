<?php

/* @var $model app\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "Запрос на восстановление пароля";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, заполните вашу почту. Ссылка на восстановление пароля будет отправлена туда.</p>
    <div class="row">
        <div class="col-lg-5">

            <?php  $form = ActiveForm::begin(['id' => 'request-password-reset-form']) ?>
            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton('Восстановить пароль', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>

        </div>
    </div>
</div>