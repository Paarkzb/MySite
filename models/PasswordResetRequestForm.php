<?php


namespace app\models;

use Yii;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'app\models\User',
                'message' => 'Пользователя с таким адресом не существует'
            ],
        ];
    }

    public function sendEmail()
    {
        /* @var $user app\models\User */
        $user = User::findOne(['email' => $this->email]);

        if(!is_object($user))
        {
            return false;
        }

        if(!User::isPasswordResetTokenValid($user->password_reset_token))
        {
            $user->generatePasswordResetToken();
            if(!$user->save())
            {
                return false;
            }
        }

        /*return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Восстановление пароля для ' . Yii::$app->name)
            ->send();*/
        return $user->password_reset_token;
    }
}