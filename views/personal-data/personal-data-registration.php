<?php
/* @var $model app\models\PersonalDataForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Address;
use app\models\School;
use kartik\select2\Select2;

$this->title = "Введите личные данные";
?>

<h1 class="text-center"><?= Html::encode($this->title); ?></h1>
<hr>

<?php $form = ActiveForm::begin(['id' => 'form-personal-area']);

$classes = ['1' => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
$addresses = Address::find()->all();
$addressesItems = ArrayHelper::map($addresses, 'address_id', 'address_title');
$schools = School::find()->all();
$schoolsItems = ArrayHelper::map($schools, 'school_id', 'school_title');

echo $form->field($model, 'family')->label('Фамилия')
    ->textInput(['autofocus' => true, 'placeholder' => 'Иванов']);
echo $form->field($model, 'name')->label('Имя')
    ->textInput(['placeholder' => 'Иван']);
echo $form->field($model, 'patronymic')->label('Отчество')
    ->textInput(['placeholder' => 'Иванович']);
	echo $form->field($model, 'address_id')->label('Район/Город')
	->widget(Select2::class, [
		'data' => $addressesItems,
		'options' => [
			'placeholder' => 'Выберите район/город',
		],
		'pluginOptions' => ['allowClear' => true],
	]);
echo $form->field($model, 'school_id')->label('Школа')
	->widget(Select2::class, [
		'data' => $schoolsItems,
		'options' => [
			'placeholder' => 'Выберите школу',
		],
		'pluginOptions' => ['allowClear' => true],
	]);
echo $form->field($model, 'class')->label('Класс')->dropDownList($classes);
echo $form->field($model, 'telephone')->label('Телефон')
    ->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+7 (999) 999 99 99',
    ])->textInput(['placeholder' => $model->getAttributeLabel('+7 (999) 999 99 99')]);
?>

<div class="form-group text-center">
	<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']); ?>
</div>

<?php
ActiveForm::end();
?>