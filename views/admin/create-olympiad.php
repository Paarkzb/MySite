<?php

/* @var $olympiad app\models\Olympiad */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Classroom;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;

$this->title = 'Создать олимпиаду';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<hr>

<?php

$form = ActiveForm::begin(['id' => 'form-olympiad']);

$subjects = ['Математика' => 'Математика', 'Информатика' => 'Информатика', 'Физика' => 'Физика'];

$classrooms = Classroom::find()->all();
$classroomsItems = ArrayHelper::map($classrooms, 'classroom_id', 'room');
?>

<?= $form->field($olympiad, 'subject')->label('Предмет')
	->dropDownList($subjects, ['prompt' => 'Выберите предмет']) ?>
<?= $form->field($olympiad, 'description')->label('Описание')->textarea(['placeholder' => 'Описание олимпиады: где, когда и прочее.'])->hint('Можно оставить пустым'); ?>
<?= $form->field($olympiad, 'date_start')
	->label('Дата начала регистрации')
	->widget(DateTimePicker::className(), [
		'name' => 'dp_1',
		'type' => DateTimePicker::TYPE_INPUT,
		'options' => ['placeholder' => 'Введите дату начала регистрации'],
		'pluginOptions' => [
			'format' => 'yyyy-mm-dd hh:ii',
			'autoclose' => true,
			'weekStart' => 1, //неделя начинается с понедельника
			'startDate' => '01-05-2015 00:00', //самая ранняя возможная дата
			'todayBtn' => true, //снизу кнопка "сегодня"
		]
	])
	->hint('Убедитесь, что дата начала регистрации раньше даты окончания'); ?>
<?= $form->field($olympiad, 'date_end')
	->label('Дата окончания регистрации')
	->widget(DateTimePicker::className(), [
		'name' => 'dp_2',
		'type' => DateTimePicker::TYPE_INPUT,
		'options' => ['placeholder' => 'Введите дату окончания регистрации'],
		'pluginOptions' => [
			'format' => 'yyyy-mm-dd hh:ii',
			'autoclose' => true,
			'weekStart' => 1, //неделя начинается с понедельника
			'startDate' => '01-05-2015 00:00', //самая ранняя возможная дата
			'todayBtn' => true, //снизу кнопка "сегодня"

		]
	])
	->hint('Убедитесь, что дата окончания регистрации не раньше даты начала'); ?>
<?= $form->field($olympiad, 'classroom_id')->label('Аудитории')
	->widget(Select2::class, [
		'data' => $classroomsItems,
		'options' => [
			'placeholder' => 'Выберите аудитории',
			'multiple' => true
		],
		'pluginOptions' => ['allowClear' => true],
	]) ?>

<?= Html::submitButton('Создать олимпиаду', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>