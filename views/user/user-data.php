<?php
/* @var $error*/
/* @var $personalData app\models\PersonalData*/

use yii\db\Query;

if($error == 1)
{
	echo "<div class='alert alert-danger'>Такой заявки нет</div>";
}
else
{
echo "
	<a href='/index.php?r=user/users' class='btn btn-success'>Вернуться</a>
	<a href='/index.php?r=user/delete-user&id=$personalData->personal_data_id' class='btn btn-danger pull-right'>Удалить участника</a>
	<h1 class='text-center'>Данные заявки №$personalData->personal_data_id</h1>
";

echo "
	<table class='table table-striped'>
		<tr>
			<td>Фамилия</td>
			<td>$personalData->family</td>
		</tr>
		<tr>
			<td>Имя</td>
			<td>$personalData->name</td>
		</tr>
		<tr>
			<td>Отчество</td>
			<td>$personalData->patronymic</td>
		</tr>
		<tr>
			<td>Район/Город</td>
			<td>{$personalData->getAddress()}</td>
		</tr>
		<tr>
			<td>Школа</td>
			<td>{$personalData->getSchool()}</td>
		</tr>
		<tr>
			<td>Класс</td>
			<td>$personalData->class</td>
		</tr>
		<tr>
			<td>Электронный адрес</td>
			<td>{$personalData->getEmail()}</td>
		</tr>
		<tr>
			<td>Телефон</td>
			<td>$personalData->telephone</td>
		</tr>
	</table>
	<div class='container'>
		<div class='text-center'>
			<a href='/index?r=user/user-data-process&id=$personalData->personal_data_id' class='btn btn-warning'>Изменить</a>
		</div>
	</div>
";
}
?>