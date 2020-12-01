<?php


namespace app\models;

use yii\base\InvalidParamException;
use yii\base\Model;

class ResetPasswordForm extends Model
{
    public $password;
    public $password_repeat;

    private $_user;

    public function __construct($token, $config = [])
    {
        if(empty($token) || !is_string($token))
        {
            throw new InvalidParamException('Токен для восстановления пароля не может быть пустым');
        }

        $this->_user = User::findByPasswordResetToken($token);

        if(!is_object($this->_user))
        {
            throw new InvalidParamException('Токен восстановления пароля неправильный');
        }

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 5],
            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false) ? $user : null;
    }
}