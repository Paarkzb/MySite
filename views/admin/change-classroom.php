<?php
/* @var $personalData app\models\PersonalData*/

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<a href='/index.php?r=admin/classrooms' class='btn btn-success'>Вернуться</a>
<h1 class='text-center'>Изменение данных аудитории <?= $classroom->room ?></h1>
<hr>


<?php $form = ActiveForm::begin(['id' => 'change-classroom']) ?>

<?= $form->field($classroom, 'room')->label('Название аудитории')
->textInput(['autofocus' => true, 'placeholder' => '1205 или актовый зал']) ?>
<?= $form->field($classroom, 'capacity')->label('Вместимость')->textInput(['placeholder' => '15']) ?>

<div class="form-group text-center">
	<?= Html::submitButton('Изменить данные аудитории', ['class' => 'btn btn-success']); ?>
</div>

<?php
ActiveForm::end();
?>