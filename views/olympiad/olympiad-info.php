<?php

use yii\helpers\Html;

$this->title = "Информация по олимпиаде";
$this->params['breadcrumbs'][] = $this->title;

?>

<a href="index.php?r=olympiad/olympiads" class="btn btn-success">Вернуться</a>

<div class="jumbotron">
	<h1><?= Html::encode($this->title) ?></h1>

	<h2> <?= $model->description   ?> </h2>
	<h2>
		Олимпиада будет проходить в следующих аудиториях:<br>
		<?php
			foreach($classrooms as $classroom){
				echo $classroom['room'] . ' ';
			}
		?>
	</h2>
	<?php
		if(sizeof($userClassroom) != 0){
			echo "
			<h2>
				Вы будете находиться в аудитории:<br>
				{$userClassroom['0']['room']}
			</h2>
			";
		}
		else{
			echo "
			<h2>
				Ваше место пока не определно
			</h2>
			";
		}
	?>
</div>

<h3 class="text-center">Список участников</h3>
<?php
echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Фамилия</th>
			<th>Имя</th>
			<th>Отчество</th>
		</tr>
	";

foreach($participants as $key => $participant)
{
	$i = $key + 1;
	echo "
		<tr>
			<td>$i</td>
			<td>{$participant['family']}</td>
			<td>{$participant['name']}</td>
			<td>{$participant['patronymic']}</td>
		</tr>
	";
}

echo "</table>";
?>