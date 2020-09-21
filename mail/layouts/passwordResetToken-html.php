<?php
 
use yii\helpers\Html;
 
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->verification_token]);

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title><?= Html::encode($this->title) ?></title>
    <!--<style type="text/css">
        .heading {...}
        .list {...}
        .footer {...}
    </style>-->
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="password-reset">
	    <p>Hai <?= Html::encode($user->username) ?>,</p>
	    <p>Kami menerima permintaan reset password dari email ini. Jika anda merasa melakukan permintaan reset password, silahkan klik link di bawah ini:</p>
        
	    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

        <p>Jika anda tidak merasa melakukan permintaan reset password tersebut, silahkan abaikan email ini.</p>
	</div>
    <div class="footer">Hormat kami, </br><b><?= Yii::$app->name ?></b> team</div>
    <?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>