<?php
 
namespace app\models\form;
 
use Yii;
use yii\base\Model;
use app\models\User;
 
/**
 * Password reset request form
 */
class PasswordResetRequest extends Model
{
    public $captcha;
    public $email;
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            [['email','captcha'], 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with such email.'
            ],
            ['captcha','captcha', 'captchaAction' => 'user/captcha', 'caseSensitive' => false],
        ];
    }
 
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);
 
        if (!$user) {
            return false;
        }
 
        if (!User::isPasswordResetTokenValid($user->verification_token)) {
            $user->generateVerificationToken();
            if (!$user->save()) {
                return false;
            }
        }

        $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->verification_token]);
 
        return Yii::$app->mailer
            ->compose('layouts/passwordResetToken-html',['user' => $user]
            )
            ->setFrom([Yii::$app->params['noreplyEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Konfirmasi reset password ' . $user->username . '(' .$user->email. ') | ' . Yii::$app->name)
            //->setTextBody('For Reset link: ' . $resetLink)
            ->send();
    }
 
}