<?php

namespace app\controllers;

use app\models\PersonalData;
use app\models\PersonalAreaForm;
use Yii;
use yii\web\Controller;


class PersonalDataController extends Controller
{

	public function actionPersonalDataRegistration()
	{
		$model = new PersonalAreaForm();

		if ($model->load(Yii::$app->request->post())) 
		{
			if ($model->save()) 
			{
				return $this->goHome();
			}
		}

		return $this->render('personal-data-registration', ['model' => $model]);
	}

	public function actionPersonalArea()
	{
		$model = PersonalData::findOne(['user_id' => Yii::$app->user->identity->user_id]);
		if (!is_object($model)) {
			$model = new PersonalData();
		}

		if($model->load(Yii::$app->request->post()))
		{
			if($model->save())
			{
				Yii::$app->session->setFlash('success', 'Ваши данные успешно изменены');
			}
			else
			{
				Yii::$app->session->setFlash('error', 'Ваши данные изменить не удалось');
			}
		}

		return $this->render('personal-area', ['model' => $model]);
	}
}
