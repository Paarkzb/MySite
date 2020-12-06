<?php

use yii\helpers\Html;

echo "<a href='index.php?r=admin/olympiad-info&id=$id' class='btn btn-success'>Вернуться</a>";

$this->title = "Список заявок на олимпиаду №$id";
$this->params['breadcrumbs'][] = $this->title;

echo "<h1 class='text-center'> ".Html::encode($this->title)." </h1>";

if(sizeof($participants) == 0){
	echo "<div class='alert alert-info text-center'>Участников нет</div>";
}
else{
	echo "
		<hr>
		<div class='row text-center'>
			<a href='/index.php?r=admin/olympiad-users&id=$id&status=2' class='btn btn-info'>Показать всех</a>
			<a href='/index.php?r=admin/olympiad-users&id=$id&status=-1' class='btn btn-info'>Не расмотренные</a>
			<a href='/index.php?r=admin/olympiad-users&id=$id&status=0' class='btn btn-info'>Отклоненные</a>
			<a href='/index.php?r=admin/olympiad-users&id=$id&status=1' class='btn btn-info'>Принятые</a>
		</div>
		<hr>
	";

	echo "
		<table class='table table-striped'>
			<tr>
				<th>№</th>
				<th>Фамилия</th>
				<th>Имя</th>
				<th>Отчество</th>
				<th>Статус</th>
				<th>Рассмотреть</th>
			</tr>
	";

	foreach($participants as $participant)
	{
		if($participant['status'] == -1){
			$userStatus = "Не расмотрен";
		}
		else if($participant['status']== 0){
			$userStatus ="Отклонен";
		}
		else{
			$userStatus ="Принят";
		}

		echo "
			<tr>
				<td>{$participant['user_data_id']}</td>
				<td>{$participant['family']}</td>
				<td>{$participant['name']}</td>
				<td>{$participant['patronymic']}</td>
				<td>{$userStatus}</td>
				<td><a href='/index.php?r=admin/user-data&id={$participant['user_id']}&o_id=$id&status=$status' class='btn btn-success'>Рассмотреть</a></td>
			</tr>
		";
	}

	echo "</table>";
}