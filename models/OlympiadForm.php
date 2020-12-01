<?php

namespace app\models;

use yii\base\Model;

class OlympiadForm extends Model{
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

}

?>