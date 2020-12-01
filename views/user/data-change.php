<?php
/* @var $personalData app\models\PersonalData*/

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\School;
use app\models\Address;
use kartik\select2\Select2;

echo "
<a href='/index.php?r=user/user-data&id=$personalData->personal_data_id' class='btn btn-success'>Вернуться</a>
<h1 class='text-center'>Изменение данных участника № $personalData->personal_data_id</h1>
<hr>
";

$form = ActiveForm::begin(['action' => 'index.php?r=user/user-data-change-process']);

$classes = ['1' => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
$addresses = Address::find()->all();
$addressesItems = ArrayHelper::map($addresses, 'address_id', 'address_title');
$schools = School::find()->all();
$schoolsItems = ArrayHelper::map($schools, 'school_id', 'school_title');

echo $form->field($personalData, 'family')->label('Фамилия')
    ->textInput(['autofocus' => true, 'placeholder' => 'Иванов']);
echo $form->field($personalData, 'name')->label('Имя')
    ->textInput(['placeholder' => 'Иван']);
echo $form->field($personalData, 'patronymic')->label('Отчество')
    ->textInput(['placeholder' => 'Иванович']);
echo $form->field($personalData, 'address_id')->label('Район/Город')
	->widget(Select2::class, [
		'data' => $addressesItems,
		'options' => [
			'placeholder' => 'Выберите район/город',
		],
		'pluginOptions' => ['allowClear' => true],
	]);
echo $form->field($personalData, 'school_id')->label('Школа')
	->widget(Select2::class, [
		'data' => $schoolsItems,
		'options' => [
			'placeholder' => 'Выберите школу',
		],
		'pluginOptions' => ['allowClear' => true],
	]);
echo $form->field($personalData, 'class')->label('Класс')->dropDownList($classes);
echo $form->field($personalData, 'telephone')->label('Телефон')
    ->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '+7 (999) 999 99 99',
    ])->textInput(['placeholder' => $personalData->getAttributeLabel('+7 (999) 999 99 99')]);
?>

<input type="hidden" name="id" 
	value="<?= $personalData->personal_data_id?>">

<div class="form-group">
	<?= Html::submitButton('Изменить данные', ['class' => 'btn btn-success']); ?>
</div>

<?php
ActiveForm::end();
?>