<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Олимпиады</h1>
		<p><a href="/index.html?r=olympiad/olympiad-request" class="btn btn-lg btn-success">Записаться на олимпиаду</a></p>
	</div>

	<div class="text-center">
		<a href="index.php?r=user/users" class="btn btn-success">Список аккаунтов</a>
		<a href="index.php?r=admin/create-olympiad" class="btn btn-success">Создать олимпиаду</a>
		<a href="index.php?r=admin/olympiads" class="btn btn-success">Список олимпиад</a>
		<a href="index.php?r=admin/create-classroom" class="btn btn-success">Создать аудиторию</a>
		<a href="index.php?r=admin/classrooms" class="btn btn-success">Список аудиторий</a>
	</div>
</div>
