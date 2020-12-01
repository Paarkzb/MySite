<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Olympiad extends ActiveRecord
{
	public function getClassrooms()
	{
		$query = "SELECT olympiad.classroom_id FROM olympiad WHERE olympiad.olympiad_id = $this->olympiad_id";
		$classrooms = Yii::$app->db->createCommand($query)->queryAll();
		print_r($classrooms);
	}
}
