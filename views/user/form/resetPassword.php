<?php
 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
 
$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>
 
<div class="user-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Silahkan pilih password baru:</p>
    <div class="row">
        <div class="col-lg-5">
 
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password_repeat')->passwordInput() ?>
                <?= $form->field($model, 'captcha')->widget(Captcha::classname(), [
                    'captchaAction' => 'user/captcha',
                    //'template' => '<div class="row"><div">{image}</div><div>{input}</div></div>',
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
 
        </div>
    </div>
</div>