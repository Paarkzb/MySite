<?php

use yii\helpers\Html;

$this->title = "Информация по олимпиаде №$id";
$this->params['breadcrumbs'][] = $this->title;

?>

<a href="index.php?r=admin/olympiads" class="btn btn-success">Вернуться</a>

<div class="jumbotron">
	<h1><?= Html::encode($this->title) ?></h1>

	<h2> <?= $model->description ?></h2>
	<h2>
		Олимпиада будет проходить в следующих аудиториях:<br>
		<?php
			foreach($classrooms as $classroom){
				echo $classroom['room'] . ' ';
			}
		?>
	</h2>
</div>

<div class="text-center">
	<a href="index.php?r=admin/olympiad-users&id=<?= $id ?>" class="btn btn-success">Список заявок</a>
	<a href="index.php?r=admin/seating&o_id=<?= $id ?>" class="btn btn-primary">Рассадить участников</a>
	<a href="index.php?r=admin/clear-classroom&o_id=<?= $id ?>" class="btn btn-danger">Отменить рассадку</a>
	<a href="index.php?r=admin/unload-olympiad-data&o_id=<?= $id ?>" class="btn btn-success">Выгрузить данные</a>
</div>