<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class PersonalData extends ActiveRecord
{

	public function rules()
	{
		return [
			[['family', 'name', 'patronymic', 'address_id', 'school_id', 'class', 'telephone'], 'required'],
			['telephone', 'string', 'min' => 18],
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

    public function getEmail()
    {
        $query = "SELECT user.email FROM user WHERE user.user_id = $this->user_id";
        $STH = Yii::$app->db->createCommand($query)->queryAll();
        if($STH)
        {
            return $STH[0]['email'];
        }
        else
        {
            return 'Ошибка!. Нет электронного адреса.';
        }
	}

	public function getAddress()
	{
		$query = "SELECT address.address_title FROM address, personal_data WHERE address.address_id = $this->address_id";
		$STH = Yii::$app->db->createCommand($query)->queryAll();
        if($STH)
        {
            return $STH[0]['address_title'];
        }
        else
        {
            return 'Ошибка! Нет адреса.';
        }
	}

	public function getSchool()
	{
		$query = "SELECT school.school_title FROM school, personal_data WHERE school.school_id = $this->school_id";
        $STH = Yii::$app->db->createCommand($query)->queryAll();
        if($STH)
        {
            return $STH[0]['school_title'];
        }
        else
        {
            return 'Ошибка! Нет школы.';
        }
	}
	
}