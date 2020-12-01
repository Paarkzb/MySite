<?php

echo "
	<table class='table table-striped'>
		<tr>
			<th>№</th>
			<th>Предмет</th>
			<th>Дата начала регистрации</th>
			<th>Дата окончания регистрации</th>
			<th>Аудитории</th>
			<th>Операции</th>
		</tr>
	";

foreach ($olympiads as $olympiad) {
	echo "
		<tr>
			<td>$olympiad->olympiad_id</td>
			<td>$olympiad->subject</td>
			<td>$olympiad->date_start</td>
			<td>$olympiad->date_end</td>
			<td>{$olympiad->classroom_id}</td>
			<td>
				<a 
				href='#' 
				class='btn btn-success'>
				Рассмотреть
				</a>
			</td>
		</tr>
	";
}

echo "</table>";
