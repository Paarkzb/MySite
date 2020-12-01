<?php


namespace app\models;


use yii\base\Model;

class OlympiadCreateForm extends Model
{
    public $subject;
    public $date_start;
    public $date_end;
    public $classroom_id;

    public function rules()
    {
        return [
            [['subject', 'date_start', 'date_end', 'classroom_id'], 'required'],
        ];
    }

    public function save()
    {
        if(!$this->validate())
        {
            return null;
        }

        $olympiad = new Olympiad();
        $olympiad->subject = $this->subject;
        $olympiad->date_start = $this->date_start;
        $olympiad->date_end = $this->date_end;
        $olympiad->classroom_id = $this->classroom_id;

        return $olympiad->save();
    }
}