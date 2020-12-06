<?php

use yii\helpers\Html;

$this->title = 'Список ваших олимпиад';
$this->params['breadcrumbs'][] = $this->title;

echo "<h1 class='text-center'> ".Html::encode($this->title)." </h1>";
echo "<hr>";

if(sizeof($olympiads) == 0){
	echo "
	<div class='alert alert-info text-center'> Вы не записаны ни на одну олимпиаду </div>
	";
}
else{
	echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Предмет</th>
			<th>Операции</th>
			<th></th>
		</tr>
	";

	foreach ($olympiads as $key => $olympiad) {
		$i = $key + 1;
		echo "
			<tr>
				<td>$i</td>
				<td>{$olympiad['subject']}</td>
				<td>
					<a href='index.php?r=olympiad/olympiad-info&id={$olympiad['olympiad_id']}' 
						class='btn btn-success'>
						Информация
					</a>
				</td>
				<td>
					<a href='index.php?r=olympiad/cancel&id={$olympiad['olympiad_id']}'
						class='btn btn-danger'>
						Отменить заявку
					</a>
				</td>
			</tr>
			";
	}
}

echo "</table>";
