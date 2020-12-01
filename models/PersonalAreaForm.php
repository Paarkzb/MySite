<?php

namespace app\models;

use yii\base\Model;
use app\models\PersonalData;

class PersonalAreaForm extends Model
{
	public $family;
	public $name;
	public $patronymic;
	public $address_id;
	public $school_id;
	public $class;
	public $telephone;

	public function rules()
	{
		return [
			[['family', 'name', 'patronymic', 'address_id', 'school_id', 'class', 'telephone'], 'required'],
			['telephone', 'string', 'min' => 18],
		];
	}

	public function save($id)
    {
        if(!$this->validate())
        {
            return null;
		}
		
		$personalData = PersonalData::findOne(['user_id' => $id]);
        $personalData->family = $this->family;
        $personalData->name = $this->name;
        $personalData->patronymic = $this->patronymic;
        $personalData->address_id = $this->address_id;
        $personalData->school_id = $this->school_id;
        $personalData->class = $this->class;
        $personalData->telephone = $this->telephone;

        return $personalData->save();
    }
}