<?php
 
namespace app\models\form;
 
use yii\base\Model;
use yii\base\InvalidParamException;
use app\models\User;
//use yii\captcha\Captcha;
 
/**
 * Password reset form
 */
class ResetPassword extends Model
{
    public $captcha;
    public $password;
    public $password_repeat;
 
    /**
     * @var \app\models\User
     */
    private $_user;
 
    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
 
        $this->_user = User::findByPasswordResetToken($token);
 
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
 
        parent::__construct($config);
    }
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeVerificationToken();
        return $user->save(false);
    }
 
}