<?php

namespace app\models;

use yii\db\ActiveRecord;

class OlympiadClassroom extends ActiveRecord
{
	public function rules()
	{
		return [
			[['olympiad_id', 'classroom_id'], 'required'],
		];
	}
}