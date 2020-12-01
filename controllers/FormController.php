<?php

namespace app\controllers;

use app\models\PersonalData;
use Yii;
use yii\web\Controller;
use app\models\OlympiadForm;

class FormController extends Controller
{
	public function actionForm()
	{
        $model = new OlympiadForm();
        if($model->load(Yii::$app->request->post()))
        {
            if($personalData = $model->apply())
            {
                $this->goHome();
            }
            else
            {
                Yii::$app->session->setFlash('error',  'Вы не записались');
            }
        }
		return $this->render('form', ['model' => $model]);
	}
}
