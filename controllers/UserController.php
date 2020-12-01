<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\PersonalData;
use app\models\Classroom;

class UserController extends Controller
{
	public function actionUsers($status = 2)
	{
		$error = 0;
		$personalData = null;
		if($status == 2)
		{
			$personalData = PersonalData::find()->all();
		}
		else{
			if($status < -1 || $status > 2)
			{
				$error = 1;
			}
			else
			{
				$personalData = PersonalData::find()->where("status = $status")->all();
			}
		}
		return $this->render('users', ['personalData' => $personalData, 
		'error' => $error, 'status' => $status]);
	}

	// Персональные данные
	public function actionUserData($id = 0, $status = -1)
	{
		$error = 0;
		$personalData = PersonalData::findOne($id);
		if(!is_object($personalData))
		{
			$error = 1;
			$userData = null;
		}
		return $this->render('user-data', ['personalData' => $personalData, 'error' => $error, 'status' => $status]);
	}

	public function actionUserDataProcess($id = 0, $value = 0, $status = -1)
	{
		$personalData = PersonalData::findOne($id);
		if(is_object($personalData))
		{
			if($value == 1)
			{
				$personalData->status = 1;
				$personalData->save();
				$this->goBack("/index?r=user/users&status=$status");
			}
			else if($value == 2)
			{
				return $this->render('data-change', ['personalData' => $personalData]);
			}
			else if($value == 3)
			{
				$personalData->status = 0;
				$personalData->save();
				$this->goBack("/index?r=user/users&status=$status");
			}
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
		$personalData->load(Yii::$app->request->post());
		$personalData->save();
		$this->goBack("/index.php?r=user/user-data&id=$personalData->personal_data_id");
	}

	public function actionDeleteUser($id = 0)
	{
		$personalData = PersonalData::findOne($id);
		if(is_object($personalData)){
			$personalData->delete();
			$this->redirect('/index.php?r=user/users');
		}
		else{
			$this->redirect('/index.php?r=site/my-error&message=Участник не найден');
		}
	}

	public function actionSeating()
	{
		$users = PersonalData::find()->orderBy('school_id')->asArray()->all();
		$classrooms = Classroom::find()->asArray()->all();

		for($i = 0; $i < sizeof($users);)
		{
			$f = 0;
			foreach($classrooms as $classroom)
			{
				if($classroom['capacity'] > 0)
				{
					if($i == sizeof($users))
					{
						break;
					}
					$user = PersonalData::findOne($users[$i]['personal_data_id']);
					$user->classroom_id = $classroom['classroom_id'];
					$user->save();
					$classroom['capacity']--;
					$i++;
					$f = 1;
				}
			}
			if(!$f)
			{
				break;//Ошибка. Участники не поместились в аудиториях.
			}
		}
		return $this->redirect('index.php?r=user/users');
	}

	public function actionClearClassroom()
	{
		$query = 'UPDATE personal_data SET personal_data.classroom_id = null';
		$STH = Yii::$app->db->createCommand($query)->execute();
		return $this->redirect('index.php?r=user/users');
	}
}

?>