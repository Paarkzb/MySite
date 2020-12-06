<?php


namespace app\models;

use yii\db\ActiveRecord;

class Classroom extends ActiveRecord
{
	public function rules()
	{
		return [
			['room', 'unique', 'targetClass' => 'app\models\Classroom', 
			'message' => 'Аудитория с таким названием уже существует'],
			[['room', 'capacity'], 'required'],
			[['room', 'capacity'], 'trim'],
		];
	}
}