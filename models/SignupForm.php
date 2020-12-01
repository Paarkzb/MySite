<?php


namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким логином
            уже существует'],
            ['username', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            ['username', 'string', 'min' => 1, 'max' => 50],
            ['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Пользователь с таким адресом 
                электронной почты уже существует'],
            ['password', 'required'],
            ['password', 'string', 'min' => 5],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function signUp()
    {
        if(!$this->validate())
        {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }
}