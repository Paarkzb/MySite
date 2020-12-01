
<div class="row text-center">
	<a href="/index.php?r=user/users&status=2" class="btn btn-info">Показать всех</a>
	<a href="/index.php?r=user/users&status=-1" class="btn btn-info">Не расмотренные</a>
	<a href="/index.php?r=user/users&status=0" class="btn btn-info">Отклоненные</a>
	<a href="/index.php?r=user/users&status=1" class="btn btn-info">Принятые</a>
</div>
<hr>
<?php
/* @var $error*/
/* @var $personalData app\models\PersonalData*/

if($error == 1){
	echo '<div class="alert alert-danger">Такого статуса нет</div>';
}
else{
	if(count($personalData) == 0){
		echo "";
	}
	else{
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

		foreach($personalData as $data)
		{
			echo "
				<tr>
					<td>$data->personal_data_id</td>
					<td>$data->family</td>
					<td>$data->name</td>
					<td>$data->patronymic</td>
					<td>{$data->getStatus()}</td>
					<td><a href='/index.php?r=user/user-data&id=$data->personal_data_id&status=$status' class='btn btn-success'>Рассмотреть</a></td>
				</tr>
			";
		}

		echo "</table>";
	}

}
?>

<div class="text-center">
	<a href="index.php?r=user/seating" class="btn btn-primary">Рассадить участников</a>
</div>