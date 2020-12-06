<?php

namespace app\controllers;

use app\models\PersonalData;
use Yii;
use yii\web\Controller;
use app\models\OlympiadForm;
use app\models\UserData;

class FormController extends Controller
{
	public function actionForm($type = 0)
	{
		if($type == 0){
			$this->redirect('/index.php?r=site/my-error&$message=Такой олимпиады нет');
		}
		$model = PersonalData::findOne(['user_id' => Yii::$app->user->identity->user_id]);
		if (!is_object($model)) {
			$model = new OlympiadForm();
		}
		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				$participant = new UserData();
				$participant->user_id = $model->user_id;
				$participant->olympiad_id = $type;
				if($participant->save()){
					$this->redirect('index.php?r=olympiad/olympiad-request');
					Yii::$app->session->setFlash('success',  'Вы успешно записались');
				}
			} else {
				Yii::$app->session->setFlash('error',  'Вы не записались');
			}
		}
		return $this->render('form', ['model' => $model, 'type' => $type]);
	}
}
