<?php


namespace app\controllers;

use app\models\OlympiadCreateForm;
use Yii;
use yii\web\Controller;
use app\models\Olympiad;

class OlympiadController extends Controller
{
    public function actionCreateOlympiad()
    {
        $olympiad = new OlympiadCreateForm();

        if($olympiad->load(Yii::$app->request->post()))
        {
            $olympiad->date_start = Yii::$app->formatter
                ->asDate($_POST['OlympiadCreateForm']['date_start'], 'yyyy-MM-dd HH:mm:ss');
            $olympiad->date_end = Yii::$app->formatter
                ->asDate($_POST['OlympiadCreateForm']['date_end'], 'yyyy-MM-dd HH:mm:ss');

			$classroomsArray = $olympiad->classroom_id;
            foreach ($classroomsArray as $value)
            {
				$model = new Olympiad();
                $model->subject = $olympiad->subject;
                $model->date_start = $olympiad->date_start;
                $model->date_end = $olympiad->date_end;
                $model->classroom_id = $value;
				$model->save();
            }
        }

        return $this->render('create-olympiad', ['olympiad' => $olympiad]);
	}
	
	public function actionOlympiads()
	{
		$olympiads = Olympiad::find()->all();

		return $this->render('olympiads', ['olympiads' => $olympiads]);
	}
}