<?php


namespace app\models;

use Yii;
use yii\base\Model;

class OlympiadCreateForm extends Model
{
	public $subject;
	public $description;
    public $date_start;
    public $date_end;
    public $classroom_id;

    public function rules()
    {
        return [
			[['subject', 'date_start', 'date_end', 'classroom_id'], 'required', 'message' => 'Поле не может быть пустым'],
			['description', 'default', 'value' => null],
        ];
    }

    public function save($id = 0)
    {
        if(!$this->validate())
        {
            return null;
        }

        if($id == 0){
			$model = new Olympiad();
		}
		else{
			$model = Olympiad::findOne($id);
			$query = "DELETE FROM olympiad_classroom WHERE olympiad_id = $id";
			$STH = Yii::$app->db->createCommand($query)->execute();
		}

		$model->subject = $this->subject;
		$model->description = $this->description;
		$model->date_start = $this->date_start;
		$model->date_end = $this->date_end;
		$model->save();

		$classroomsArray = $this->classroom_id;
		foreach ($classroomsArray as $value) {
			$olympiadClassroom = new OlympiadClassroom();
			$olympiadClassroom->olympiad_id = $model->olympiad_id;
			$olympiadClassroom->classroom_id = $value;
			$olympiadClassroom->save();
		}

        return $model->save() ? $model : null;
    }
}