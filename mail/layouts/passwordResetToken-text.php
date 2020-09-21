<?php

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->verification_token]);
?>
Hai <?= $user->username ?>,
Kami menerima permintaan reset password dari email ini. Jika anda merasa melakukan permintaan reset password, silahkan klik link di bawah ini:
        
<?= $resetLink ?>

Jika anda tidak merasa melakukan permintaan reset password tersebut, silahkan abaikan email ini.

Hormat kami, 
<?= Yii::$app->name ?> team