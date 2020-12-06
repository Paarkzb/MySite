<?php


namespace app\controllers;

use app\models\OlympiadCreateForm;
use Yii;
use yii\web\Controller;
use app\models\Olympiad;
use app\models\UserData;
use app\models\PersonalData;

class OlympiadController extends Controller
{
	public function actionOlympiadRequest()
	{
		$olympiads = Olympiad::find()->all();

		return $this->render('olympiad-request', ['olympiads' => $olympiads]);
	}

	public function actionOlympiadInfo($id = 0)
	{
		if ($id > 0) {
			$query = "SELECT pd.family, pd.name, pd.patronymic FROM personal_data as pd, user_data as ud 
			WHERE pd.user_id = ud.user_id AND ud.olympiad_id = $id";
			$STH = Yii::$app->db->createCommand($query)->queryAll();

			$model = Olympiad::findOne($id);


			$query = "SELECT c.room FROM classroom as c, olympiad_classroom as oc 
			WHERE c.classroom_id = oc.classroom_id AND oc.olympiad_id = $model->olympiad_id";
			$STH1 = Yii::$app->db->createCommand($query)->queryAll();

			$userId = Yii::$app->user->identity->user_id;
			$query = "SELECT c.room FROM classroom as c, user_data as ud
			WHERE c.classroom_id = ud.classroom_id AND ud.olympiad_id = $id AND ud.user_id = $userId";
			$STH2 = Yii::$app->db->createCommand($query)->queryAll();
			if(is_object($model)){
				return $this->render('olympiad-info', ['model' => $model, 'participants' => $STH, 
				'classrooms' => $STH1, 'userClassroom' => $STH2]);
			}
			else{
				$this->redirect('/index.php?r=site/my-error&message=Такой олимпиады нет');
			}
		}
		else{
			$this->redirect('/index.php?r=site/my-error&message=Такой олимпиады нет');
		}
	}

	

	public function actionOlympiads()
	{
		// в main добавить кнопку список олимпиад. В ней олимпиады пользователя на которых он записан.
		// если олимпиад нет, то вывести соотвествующее сообщение.
		$userId = Yii::$app->user->identity->user_id;
		$query = "SELECT olympiad.* FROM olympiad, user_data as ud WHERE olympiad.olympiad_id = ud.olympiad_id AND ud.user_id = $userId";
		$olympiads = Yii::$app->db->createCommand($query)->queryAll();

		return $this->render('olympiads', ['olympiads' => $olympiads]);
	}

	public function actionCancel($id = 0)
	{
		if($id > 0){
			$userId = Yii::$app->user->identity->user_id;
			$model = UserData::findOne(["user_id" => $userId, "olympiad_id" => $id]);
			if(is_object($model)){
				$model->delete();
				$this->redirect('index.php?r=olympiad/olympiads');
			}
		}
		else{
			$this->redirect('/index.php?r=site/my-error&message=Такой олимпиады нет');
		}
	}
}
