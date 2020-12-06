<?php

use yii\helpers\Html;

$this->title = 'Список олимпиад';
$this->params['breadcrumbs'][] = $this->title;

echo "<h1 class='text-center'> ".Html::encode($this->title)." </h1>";
echo "<hr>";

echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Предмет</th>
			<th>Операции</th>
			<th></th>
			<th></th>
		</tr>
	";

foreach ($olympiads as $olympiad) {
	echo "
		<tr>
			<td>$olympiad->olympiad_id</td>
			<td>$olympiad->subject</td>
			<td>
				<a href='index.php?r=admin/olympiad-info&id=$olympiad->olympiad_id' 
					class='btn btn-success'>
					Информация
				</a>
			</td>
			<td>
				<a href='index.php?r=admin/change-olympiad&id=$olympiad->olympiad_id' 
					class='btn btn-warning'>
					Изменить
				</a>
			</td>
			<td>
				<a href='index.php?r=admin/delete-olympiad&id=$olympiad->olympiad_id'
					class = 'btn btn-danger'>
					Удалить олимпиаду
				</a>
			</td>
		</tr>
		";
}

echo "</table>";
