<?php

namespace app\models\form;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $email;
    public $password;
    public $captcha;
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'match','pattern' => "/(?=.*\d)/",'message' => 'Minimal Password harus 1 angka'],
            ['password', 'match','pattern' => "/(?=.*[a-z])/",'message' => 'Minimal Password harus 1 huruf kecil'],
            ['password', 'match','pattern' => "/(?=.*[A-Z])/",'message' => 'Minimal Password harus 1 huruf kapital'],
            ['password', 'match','pattern' => "/\w{6,}/",'message' => 'Minimal Password harus 6 karakter'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'message'=>"Passwords don't match"],
            ['captcha','captcha', 'captchaAction' => 'user/captcha', 'caseSensitive' => false],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
       
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $data = [
            'token' => $user->generateVerificationToken(),
            'username' => $this->username,
            'email' => $this->email,
            'autKey' => $user->generateAuthKey(),
        ];
        $this->sendEmail($data);
        return $user->save();
    }

    public function sendEmail($data)
    {
        $user = User::findByInactiveUsername($data['username']);
        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/email-verification', 'token' => $data['token']]);
 
        return Yii::$app->mailer
            ->compose()
            ->setFrom([Yii::$app->params['noreplyEmail'] => Yii::$app->name])
            ->setTo($data['email'])
            ->setSubject('Accout Verification of ' . $data['username'] . "/" . $data['email'])
            ->setTextBody('Klik link berikut untuk mengaktifkan account: <br>' . PHP_EOL . $resetLink)
            ->send();
    }
}
