<?php

use yii\helpers\Html;

$this->title = 'Список олимпиад для участия';
$this->params['breadcrumbs'][] = $this->title;

echo "<h1 class='text-center'> ".Html::encode($this->title)." </h1>";
echo "<hr>";

use app\models\UserData;
echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Предмет</th>
			<th>Дата начала регистрации</th>
			<th>Дата окончания регистрации</th>
			<th>Записаться</th>
		</tr>
	";

foreach ($olympiads as $key => $olympiad) {
	$participant = UserData::findOne(['user_id' => Yii::$app->user->identity->user_id, 'olympiad_id' => $olympiad->olympiad_id]);
	$i = $key + 1;
	echo "
		<tr>
			<td>$i</td>
			<td>$olympiad->subject</td>
			<td>$olympiad->date_start</td>
			<td>$olympiad->date_end</td>
	";
	if(is_object($participant)){
		echo "
			<td>
				<p class='alert alert-danger'> Вы уже записаны на эту олимпиаду </p>
			</td>
		";
	}
	else{
		$time = strtotime('+7 hour');
		if($olympiad->date_end < date("Y-m-d H:i:s", $time)){
			echo "
			<td>
				<p class='alert alert-danger'> Регистрация завершена </p>
			</td>
		</tr>
		";
		}
		else{
			echo "
			<td>
				<a 
				href='/index.php?r=form/form&type=$olympiad->olympiad_id' 
				class='btn btn-success'>
				Записаться на олимпиаду
				</a>
			</td>
		</tr>
		";
		}
	}
}

echo "</table>";
