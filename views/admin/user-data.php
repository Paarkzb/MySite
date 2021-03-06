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
	<a href='/index.php?r=admin/olympiad-users&id=$o_id&status=$status' class='btn btn-success'>Вернуться</a>
	<a href='/index.php?r=admin/delete-user&id=$userData->user_data_id&o_id=$o_id&status=$status' class='btn btn-danger pull-right'>Удалить заявку</a>
	<h1 class='text-center'>Данные заявки №$userData->user_data_id</h1>
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
		<tr>
			<td>Статус заявки</td>
			<td>{$userData->getStatus()}</td>
		</tr>
		<tr>
			<td>Аудитория</td>
			<td>{$userData->getClassroom()}</td>
		</tr>
	</table>
	<div class='container'>
		<div class='text-center'>
			<a href='/index?r=admin/user-data-process&id=$userData->user_data_id&o_id=$o_id&value=1&status=$status' class='btn btn-success'>Принять</a>
			<a href='/index?r=admin/user-data-process&id=$userData->user_data_id&o_id=$o_id&value=2&status=$status' class='btn btn-warning'>Изменить</a>
			<a href='/index?r=admin/user-data-process&id=$userData->user_data_id&o_id=$o_id&value=3&status=$status' class='btn btn-danger'>Отклонить</a>
		</div>
	</div>
";
}
?>