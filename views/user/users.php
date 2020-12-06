<?php

use yii\helpers\Html;

$this->title = 'Личные данные участников';
$this->params['breadcrumbs'][] = $this->title;

echo "<h1 class='text-center'> ".Html::encode($this->title)." </h1>";
echo "<hr>";

/* @var $personalData app\models\PersonalData*/

if(count($personalData) == 0){
	echo "";
}
else{
	echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Фамилия</th>
			<th>Имя</th>
			<th>Отчество</th>
			<th>Рассмотреть</th>
		</tr>
	";

	foreach($personalData as $key => $data)
	{
		$i = $key + 1;
		echo "
			<tr>
				<td>$i</td>
				<td>$data->family</td>
				<td>$data->name</td>
				<td>$data->patronymic</td>
				<td><a href='/index.php?r=user/user-data&id=$data->personal_data_id' class='btn btn-success'>Рассмотреть</a></td>
			</tr>
		";
	}

	echo "</table>";
}
?>

<div class="text-center">
	<a href="index.php?r=admin/unload-users-data" class="btn btn-success">Выгрузить данные</a>
</div>