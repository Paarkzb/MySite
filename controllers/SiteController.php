<?php

namespace app\controllers;

use app\models\PasswordResetRequestForm;
use app\models\PersonalData;
use app\models\ResetPasswordForm;
use app\models\User;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
	}

	public function actionMyError($message = ''){
		if ($message != null) {
			return $this->render('my-error', ['message' => $message]);
		}
	}

	public function actionSignup()
    {
        $model = new SignupForm();

        if($model->load(Yii::$app->request->post()))
        {
            if($user = $model->signup())
            {
                $personalData = new PersonalData();
                $personalData->user_id = $user->user_id;
                $personalData->save(false);
                if(Yii::$app->getUser()->login($user))
                {
					$this->redirect("index.php?r=personal-data/personal-data-registration&id=$user->user_id");
                }
            }
        }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if($token = $model->sendEmail())
            {
                Yii::$app->session->setFlash('success',  'Проверьте вашу почту для дальнейших инструкции');
//                $this->goHome();
                $this->redirect("/index.php?r=/site/reset-password&token=$token");
            }
            else
            {
                Yii::$app->session->setFlash(
                    'error',
                    'Извините, мы не можем сбросить пароль для указанного адреса электронной почты'
                );
            }
        }

        return $this->render('request-password-reset', ['model' => $model]);
    }

    public function actionResetPassword($token)
    {
        try
        {
            $model = new ResetPasswordForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if($model->load(Yii::$app->request->post()) && $model->validate() && $user = $model->resetPassword())
        {
            if(Yii::$app->getUser()->login($user))
            {
                $this->goHome();
            }
        }

        return $this->render('reset-password', ['model' => $model]);
	}
	
	public function actionAddAdmin()
	{
		$model = new User();
		$model->username = 'admin';
		$model->generateAuthKey();
		$model->setPassword('admin');
		$model->email=Yii::$app->params['adminEmail'];
		$model->status = 1;
		$model->save();
	}
}
