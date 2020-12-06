<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Создать аудиторию';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<hr>

<?php $form = ActiveForm::begin(['id' => 'form-classroom']); ?>

<?= $form->field($model, 'room')->label('Название аудитории')
->textInput(['autofocus' => true, 'placeholder' => '1205 или актовый зал']) ?>
<?= $form->field($model, 'capacity')->label('Вместимость')->textInput(['placeholder' => '15']) ?>




<?= Html::submitButton('Создать аудиторию', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>