<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\PersonalData;
use app\models\Classroom;
use app\models\UserData;

class UserController extends Controller
{
	public function actionUsers()
	{
		$personalData = PersonalData::find()->all();
		return $this->render('users', ['personalData' => $personalData]);
	}

	// Персональные данные
	public function actionUserData($id = 0)
	{
		$error = 0;
		$personalData = PersonalData::findOne($id);
		if(!is_object($personalData))
		{
			$error = 1;
			$userData = null;
		}
		return $this->render('user-data', ['personalData' => $personalData, 'error' => $error]);
	}

	public function actionUserDataProcess($id = 0)
	{
		$personalData = PersonalData::findOne($id);
		if(is_object($personalData))
		{
			return $this->render('data-change', ['personalData' => $personalData]);
		}
		else
		{
			$this->redirect('/index.php?r=site/my-error&message=Участник не найден');
		}
	}

	public function actionUserDataChangeProcess()
	{
		$personalData = new PersonalData();
		if(is_numeric($_POST['id']))
		{
			$personalData = PersonalData::findOne($_POST['id']);
			if(!is_object($personalData))
			{
				$personalData = new PersonalData();
			}
		}
		if($personalData->load(Yii::$app->request->post())){
			if($personalData->save()){
				$this->goBack("/index.php?r=user/user-data&id=$personalData->personal_data_id");
			}
		}
	}

	public function actionDeleteUser($id = 0)
	{
		$personalData = PersonalData::findOne($id);
		if(is_object($personalData)){
			$personalData->delete();
			$user = User::findOne($personalData->user_id);
			if(is_object($user)){
				$user->delete();
			}
			$userData = UserData::findOne($personalData->user_id);
			while(is_object($userData)){
				$userData->delete();
				$userData = UserData::findOne($personalData->user_id);
			}
		}
		else{
			$this->redirect('/index.php?r=site/my-error&message=Участник не найден');
		}
		$this->redirect('/index.php?r=user/users');
	}
}

?>