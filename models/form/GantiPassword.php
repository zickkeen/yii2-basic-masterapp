<?php

namespace app\models\form;

use Yii;
use app\models\User;
use yii\base\Model;

/**
 * Description of ChangePassword
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class GantiPassword extends Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'retypePassword'], 'required'],
            [['oldPassword'], 'validatePassword'],
            [['newPassword'], 'string', 'min' => 6],
            ['newPassword', 'match','pattern' => "/(?=.*\d)/",'message' => 'Minimal Password harus 1 angka'],
            ['newPassword', 'match','pattern' => "/(?=.*[a-z])/",'message' => 'Minimal Password harus 1 huruf kecil'],
            ['newPassword', 'match','pattern' => "/(?=.*[A-Z])/",'message' => 'Minimal Password harus 1 huruf kapital'],
            ['newPassword', 'match','pattern' => "/\w{6,}/",'message' => 'Minimal Password harus 6 karakter'],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Password Lama',
            'newPassword' => 'Password Baru',
            'retypePassword' => 'Konfirmasi Password',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->oldPassword)) {
            $this->addError('oldPassword', 'Incorrect old password.');
        }
    }

    /**
     * Change password.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}