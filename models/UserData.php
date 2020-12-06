<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserData extends ActiveRecord{
	public function rules(){
		return [
			['classroom_id', 'default', 'value' => null],
			['result', 'default', 'value' => null],
			['status', 'default', 'value' => -1],
		];
	}

	public function getStatus()
    {
        if($this->status == -1){
            return "Не расмотрен";
        }
        else if($this->status == 0){
            return "Отклонен";
        }
        else{
            return "Принят";
        }
	}
	
	public function getClassroom()
    {
        if($this->classroom_id == null){
            return "Не рассажен";
        }
        else{
			$classroom = Classroom::findOne($this->classroom_id);
            return "$classroom->room";
        }
    }
}
?>