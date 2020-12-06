<?php


namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\OlympiadCreateForm;
use app\models\Olympiad;
use app\models\UserData;
use app\models\PersonalData;
use app\models\OlympiadClassroom;
use app\models\Classroom;
use app\models\ClassroomForm;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{
	public function actionCreateOlympiad()
	{
		$olympiad = new OlympiadCreateForm();

		if ($olympiad->load(Yii::$app->request->post())) {
			if($olympiad->date_end > $olympiad->date_start){
				if ($olympiad->save()) {
					$this->redirect('index.php?r=admin/create-olympiad');
				} else {
					Yii::$app->session->setFlash('error', 'Не удалось создать олимпиаду');
				}
			}
			else{
				Yii::$app->session->setFlash('error', 'Дата окончания олимпиады должна быть больше даты начала');
			}
		}

		return $this->render('create-olympiad', ['olympiad' => $olympiad]);
	}

	public function actionOlympiads()
	{
		$olympiads = Olympiad::find()->all();

		return $this->render('olympiads', ['olympiads' => $olympiads]);
	}

	public function actionOlympiadInfo($id = 0)
	{
		if ($id > 0) {
			$model = Olympiad::findOne($id);

			$query = "SELECT c.room 
						FROM classroom as c, olympiad_classroom as oc 
						WHERE c.classroom_id = oc.classroom_id AND oc.olympiad_id = $model->olympiad_id";
			$STH = Yii::$app->db->createCommand($query)->queryAll();
			if (is_object($model)) {
				return $this->render('olympiad-info', ['model' => $model, 'id' => $id, 'classrooms' => $STH]);
			} else {
				$this->redirect('/index.php?r=site/my-error&message=Такой олимпиады нет');
			}
		} else {
			$this->redirect('/index.php?r=site/my-error&message=Такой олимпиады нет');
		}
	}

	public function actionChangeOlympiad($id)
	{
		$olympiad = Olympiad::findOne($id);
		if(!is_object($olympiad)){
			$olympiad = new Olympiad();
		}

		$model = new OlympiadCreateForm();

		$model->subject = $olympiad->subject;
		$model->description = $olympiad->description;
		$model->date_start = $olympiad->date_start;
		$model->date_end = $olympiad->date_end;

		if($model->load(Yii::$app->request->post())){
			if($model->date_end > $model->date_start){
				if($model->save($id)){
					$query = "UPDATE user_data SET classroom_id = null WHERE olympiad_id = $id";
					$STH = Yii::$app->db->createCommand($query)->execute();
					$this->redirect('index.php?r=admin/olympiads');
				} 
				else {
					Yii::$app->session->setFlash('error', 'Не удалось создать олимпиаду');
				}
			}
			else{
				Yii::$app->session->setFlash('error', 'Дата окончания олимпиады должна быть больше даты начала');
			}
		}

		return $this->render('change-olympiad', ['olympiad' => $model, 'id' => $olympiad->olympiad_id]);
	}

	public function actionOlympiadUsers($id = 0, $status = 2)
	{
		if ($id > 0) {
			$error = 0;
			if ($status == 2) {
				$query = "SELECT ud.user_data_id, pd.user_id, pd.family, pd.name, pd.patronymic, ud.status 
					FROM personal_data as pd, user_data as ud 
					WHERE pd.user_id = ud.user_id AND ud.olympiad_id = $id";
				$STH = Yii::$app->db->createCommand($query)->queryAll();
			} else {
				if ($status < -1 || $status > 2) {
					$error = 1;
				} else {
					$query = "SELECT ud.user_data_id, pd.user_id, pd.family, pd.name, pd.patronymic, ud.status 
						FROM personal_data as pd, user_data as ud 
						WHERE pd.user_id = ud.user_id AND ud.olympiad_id = $id AND ud.status = $status";
					$STH = Yii::$app->db->createCommand($query)->queryAll();
				}
			}

			return $this->render('olympiad-users', ['participants' => $STH, 'id' => $id, 'status' => $status]);
		} else {
			$this->redirect('/index.php?r=site/my-error&message=Такой олимпиады нет');
		}
	}

	public function actionUserData($id = 0, $o_id = 0, $status = -1)
	{
		$error = 0;
		$userData = UserData::findOne(['user_id' => $id, 'olympiad_id' => $o_id]);
		$personalData = PersonalData::findOne(['user_id' => $id]);
		if (!is_object($personalData) && !is_object($userData)) {
			$error = 1;
			$personalData = null;
		}
		return $this->render('user-data', [
			'personalData' => $personalData,
			'userData' => $userData, 'error' => $error, 'status' => $status, 'o_id' => $o_id
		]);
	}

	public function actionUserDataProcess($id = 0, $o_id = 0, $value = 0, $status = -1)
	{
		$userData = UserData::findOne($id);
		if (is_object($userData)) {
			if ($value == 1) {
				$userData->status = 1;
				$userData->save();
				$this->goBack("index?r=admin/olympiad-users&id=$o_id&status=$status");
			} else if ($value == 2) {
				$personalData = PersonalData::findOne(['user_id' => $userData->user_id]);
				return $this->render('user-data-change-process', [
					'personalData' => $personalData, 'o_id' => $o_id,
					'status' => $status
				]);
			} else if ($value == 3) {
				$userData->status = 0;
				$userData->save();
				$this->goBack("index?r=admin/olympiad-users&id=$o_id&status=$status");
			} else {
				$this->redirect('index.php?r=site/my-error&message=Неверная операция');
			}
		} else {
			$this->redirect('index.php?r=site/my-error&message=Участник не найден');
		}
	}

	public function actionUserDataChangeProcess()
	{
		if (is_numeric($_POST['id'])) {
			$personalData = PersonalData::findOne($_POST['id']);
			if (!is_object($personalData)) {
				$this->redirect('index.php?r=site/my-error&message=Участник не найден');
			}
			if ($personalData->load(Yii::$app->request->post())) {
				if ($personalData->save()) {

					$this->goBack("index.php?r=admin/user-data&id=$personalData->user_id&o_id={$_POST['o_id']}&status={$_POST['status']}");
				}
			}
		}
	}

	public function actionCreateClassroom()
	{
		$model = new ClassroomForm();

		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->save()){
				$this->redirect('index.php?r=admin/classrooms');
			}
			else{
				Yii::$app->session->setFlash('error', 'Аудиторию создать не удалось');
			}
		}

		return $this->render('create-classroom', ['model' => $model]);
	}

	public function actionClassrooms()
	{
		$classrooms = Classroom::find()->all();

		return $this->render('classrooms', ['classrooms' => $classrooms]);
	}

	public function actionChangeClassroom($id)
	{
		$classroom = Classroom::findOne($id);
		if(!is_object($classroom)){
			$classroom = new Classroom();
		}

		if($classroom->load(Yii::$app->request->post())){
			if($classroom->save()){
				$this->goBack('index.php?r=admin/classrooms');
			}
		}

		return $this->render('change-classroom', ['classroom' => $classroom]);
	}

	public function actionDeleteUser($id = 0, $o_id = 0, $status = 2)
	{
		$userData = UserData::findOne($id);
		if (is_object($userData)) {
			$userData->delete();
			$this->redirect("index.php?r=admin/olympiad-users&id=$o_id&status=$status");
		} else {
			$this->redirect('/index.php?r=site/my-error&message=Такой заявки нет');
		}
	}

	public function actionDeleteOlympiad($id = 0)
	{
		$model = Olympiad::findOne($id);
		if (is_object($model)) {
			$query = "DELETE FROM olympiad_classroom WHERE olympiad_id = $id";
			$STH = Yii::$app->db->createCommand($query)->execute();

			$query = "DELETE FROM user_data WHERE olympiad_id = $id";
			$STH = Yii::$app->db->createCommand($query)->execute();

			$model->delete();
			$this->redirect('index.php?r=admin/olympiads');
		}
	}

	public function actionDeleteClassroom($id)
	{
		$classroom = Classroom::findOne($id);

		if(is_object($classroom)){
			$classroom->delete();
			Yii::$app->session->setFlash('success', 'Аудитория удалена');
		}

		$this->redirect('index.php?r=admin/classrooms');
	}

	public function actionSeating($o_id = 0)
	{
		$query = "SELECT ud.*
		FROM user_data as ud, personal_data as pd
		WHERE ud.user_id = pd.user_id AND ud.olympiad_id = $o_id AND ud.status != 0";
		$users = Yii::$app->db->createCommand($query)->queryAll();

		$query = "SELECT c.*
		FROM classroom as c, olympiad_classroom as oc
		WHERE c.classroom_id = oc.classroom_id AND oc.olympiad_id = $o_id";
		$classrooms = Yii::$app->db->createCommand($query)->queryAll();

		$f = 0;
		for ($i = 0; $i < sizeof($users); $i++) {
			if ($users[$i]['status'] == -1) {
				$f = 0;
				break;
			} else {
				$f = 1;
			}
			echo $f . '<br>';
		}
		if (!$f) {
			Yii::$app->session->setFlash('error', 'Не все заявки рассмотрены');
			return $this->redirect("index.php?r=admin/olympiad-info&id=$o_id");
		}

		for ($i = 0; $i < sizeof($users);) {
			$f = 0;
			foreach ($classrooms as $classroom) {
				if ($classroom['capacity'] > 0) {
					if ($i == sizeof($users)) {
						break;
					}
					$user = UserData::findOne($users[$i]['user_data_id']);
					$user->classroom_id = $classroom['classroom_id'];
					$user->save();
					$classroom['capacity']--;
					$i++;
					$f = 1;
				}
			}
			if (!$f) {
				$query = "UPDATE user_data SET user_data.classroom_id = null WHERE user_data.olympiad_id = $o_id";
				$STH = Yii::$app->db->createCommand($query)->execute();
				Yii::$app->session->setFlash('error', 'Не удалось рассадить участников, мест не хватает!');
				break;
			}
		}
		if ($f) {
			Yii::$app->session->setFlash('success', 'Все принятые участники рассажены');
		}
		$this->redirect("index.php?r=admin/olympiad-info&id=$o_id");
	}

	public function actionClearClassroom($o_id = 0)
	{
		$query = "UPDATE user_data SET user_data.classroom_id = null WHERE user_data.olympiad_id = $o_id";
		$STH = Yii::$app->db->createCommand($query)->execute();
		Yii::$app->session->setFlash('success', 'Рассадка отменена');
		$this->redirect("index.php?r=admin/olympiad-info&id=$o_id");
	}

	public function actionUnloadOlympiadData($o_id)
	{
		// Данные олимпиады
		$spreadsheet = new Spreadsheet();
		$spreadsheet->setActiveSheetIndex(0);
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(25);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(13);

		$sheet->getStyle('A1:F2')->getAlignment()
			->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$olympiadTitle = Olympiad::findOne($o_id);
		$userData = UserData::findOne(['olympiad_id' => $o_id]);
		$personalData = PersonalData::findOne(['user_id' => $userData->user_id]);
		$sheet->mergeCells('A1:F1')->setCellValue('A1', $olympiadTitle->subject . ' для ' . $personalData->class . ' класса')
			->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', 'Фамилия')->getStyle('A2')->getFont()->setBold(true);
		$sheet->setCellValue('B2', 'Имя')->getStyle('B2')->getFont()->setBold(true);
		$sheet->setCellValue('C2', 'Отчество')->getStyle('C2')->getFont()->setBold(true);
		$sheet->setCellValue('D2', 'Район/Город')->getStyle('D2')->getFont()->setBold(true);
		$sheet->setCellValue('E2', 'Школа')->getStyle('E2')->getFont()->setBold(true);
		$sheet->setCellValue('F2', 'Аудитория')->getStyle('F2')->getFont()->setBold(true);

		$query = "SELECT family, name, patronymic, address_title, school_title, COALESCE(room, 'Не рассажен')
				FROM personal_data as pd INNER JOIN user_data as ud
				ON pd.user_id = ud.user_id LEFT JOIN address
				ON pd.address_id = address.address_id LEFT JOIN school
				ON pd.school_id = school.school_id LEFT JOIN classroom
				ON ud.classroom_id = classroom.classroom_id
				WHERE ud.olympiad_id = $o_id AND ud.status = 1
				ORDER BY pd.family";
		$arr = Yii::$app->db->createCommand($query)->queryAll();

		$sheet->fromArray($arr, null, 'A3');

		$writer = new Xlsx($spreadsheet);
		$writer->save('../export/olympiad_№' . $o_id . '_' . date('d-m-yy') . '.xlsx');

		// Данные аудитории
		$query = "SELECT DISTINCT c.classroom_id, c.room FROM user_data as ud, classroom as c WHERE olympiad_id = $o_id AND ud.classroom_id = c.classroom_id";
		$classrooms = Yii::$app->db->createCommand($query)->queryAll();

		foreach ($classrooms as $classroom) {
			$userData = UserData::findOne(['olympiad_id' => $o_id, 'classroom_id' => $classroom['classroom_id']]);
			if (is_object($userData)) {
				$sheet = $spreadsheet->createSheet();

				$sheet->getColumnDimension('A')->setWidth(20);
				$sheet->getColumnDimension('B')->setWidth(20);
				$sheet->getColumnDimension('C')->setWidth(20);
				$sheet->getColumnDimension('D')->setWidth(25);
				$sheet->getColumnDimension('E')->setWidth(30);

				$sheet->getStyle('A1:E2')->getAlignment()
					->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
					->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

				$olympiadTitle = Olympiad::findOne($o_id);
				$userData = UserData::findOne(['olympiad_id' => $o_id]);
				$personalData = PersonalData::findOne(['user_id' => $userData->user_id]);
				$sheet->mergeCells('A1:E1')->setCellValue('A1', 'Аудитория ' . $classroom['room'])
					->getStyle('A1')->getFont()->setBold(true);

				$sheet->setCellValue('A2', 'Фамилия')->getStyle('A2')->getFont()->setBold(true);
				$sheet->setCellValue('B2', 'Имя')->getStyle('B2')->getFont()->setBold(true);
				$sheet->setCellValue('C2', 'Отчество')->getStyle('C2')->getFont()->setBold(true);
				$sheet->setCellValue('D2', 'Район/Город')->getStyle('D2')->getFont()->setBold(true);
				$sheet->setCellValue('E2', 'Школа')->getStyle('E2')->getFont()->setBold(true);

				$query = "SELECT family, name, patronymic, address_title, school_title
				FROM personal_data as pd INNER JOIN user_data as ud
				ON pd.user_id = ud.user_id LEFT JOIN address
				ON pd.address_id = address.address_id LEFT JOIN school
				ON pd.school_id = school.school_id
				WHERE ud.olympiad_id = $o_id AND ud.status = 1 AND ud.classroom_id = {$classroom['classroom_id']}
				ORDER BY pd.family";
				$arr = Yii::$app->db->createCommand($query)->queryAll();

				$sheet->fromArray($arr, null, 'A3');

				$writer = new Xlsx($spreadsheet);
				$writer->save('../export/olympiad_№' . $o_id . '_' . date('d-m-yy') . '.xlsx');
			}
		}

		Yii::$app->session->setFlash('success', 'Данные успешно выгружены');

		$this->redirect("index.php?r=admin/olympiad-info&id=$o_id");
	}

	public function actionUnloadUsersData()
	{
		$spreadsheet = new Spreadsheet();
		$spreadsheet->setActiveSheetIndex(0);
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->getColumnDimension('A')->setWidth(20);
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20);
		$sheet->getColumnDimension('D')->setWidth(25);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(7);

		$sheet->getStyle('A1:G2')->getAlignment()
			->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$sheet->mergeCells('A1:F1')->setCellValue('A1', 'Все участники')->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', 'Фамилия')->getStyle('A2')->getFont()->setBold(true);
		$sheet->setCellValue('B2', 'Имя')->getStyle('B2')->getFont()->setBold(true);
		$sheet->setCellValue('C2', 'Отчество')->getStyle('C2')->getFont()->setBold(true);
		$sheet->setCellValue('D2', 'Район/Город')->getStyle('D2')->getFont()->setBold(true);
		$sheet->setCellValue('E2', 'Школа')->getStyle('E2')->getFont()->setBold(true);
		$sheet->setCellValue('F2', 'Класс')->getStyle('F2')->getFont()->setBold(true);

		$query = "SELECT family, name, patronymic, address_title, school_title, class
				FROM personal_data as pd LEFT JOIN address
				ON pd.address_id = address.address_id LEFT JOIN school
				ON pd.school_id = school.school_id
				ORDER BY family";
		$arr = Yii::$app->db->createCommand($query)->queryAll();

		$sheet->fromArray($arr, null, 'A3');

		$writer = new Xlsx($spreadsheet);
		$writer->save('../export/participants_' . date('d-m-yy') . '.xlsx');

		Yii::$app->session->setFlash('success', 'Данные успешно выгружены');

		$this->redirect("index.php?r=user/users");
	}
}
