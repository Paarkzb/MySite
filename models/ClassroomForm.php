<?php

namespace app\models;

use yii\base\Model;
use app\models\Classroom;

class ClassroomForm extends Model
{

	public $room;
	public $capacity;

	public function rules()
	{
		return [
			['room', 'unique', 'targetClass' => 'app\models\Classroom', 
			'message' => 'Аудитория с таким названием уже существует'],
			[['room', 'capacity'], 'required'],
			[['room', 'capacity'], 'trim'],
		];
	}

	public function save()
	{
		if(!$this->validate())
        {
            return null;
        }

		$classroom = new Classroom();
		$classroom->room = $this->room;
		$classroom->capacity = $this->capacity;
		
		return $classroom->save() ? $classroom : null;
	}
}