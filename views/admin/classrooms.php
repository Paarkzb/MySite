<?php

use yii\helpers\Html;

$this->title = 'Список аудиторий';
$this->params['breadcrumbs'][] = $this->title;

echo "<h1 class='text-center'> ".Html::encode($this->title)." </h1>";
echo "<hr>";

echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Название</th>
			<th>Вместимость</th>
			<th>Операции</th>
			<th></th>
		</tr>
	";

foreach ($classrooms as $classroom) {
	echo "
		<tr>
			<td>$classroom->classroom_id</td>
			<td>$classroom->room</td>
			<td>$classroom->capacity</td>
			<td>
				<a href='index.php?r=admin/change-classroom&id=$classroom->classroom_id'
					class = 'btn btn-warning'>
					Изменить
				</a>
			</td>
			<td>
				<a href='index.php?r=admin/delete-classroom&id=$classroom->classroom_id'
					class = 'btn btn-danger'>
					Удалить аудиторию
				</a>
			</td>
		</tr>
		";
}

echo "</table>";
