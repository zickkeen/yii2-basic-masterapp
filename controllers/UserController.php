<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\form\Signup;
use app\models\form\Login;
use app\models\form\PasswordResetRequest;
use app\models\form\ResetPassword;
use app\models\form\GantiPassword;
use app\models\User;


class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV ? 'testme' : null,
            ],
        ];
    }

    public function actionSignup()
    {
        $model = new Signup();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Terimakasih telah mendaftar, silahkan cek E-Mail anda untuk verifikasi.');
            return $this->redirect(['user/login']);
        }

        return $this->render('form/signup', [
            'model' => $model,
        ]);
    }

    public function actionEmailVerification()
    {
        $token = Yii::$app->request->get('token');
        $user = User::findByVerificationEmailToken($token);
        if(!empty($user)){
            $user->activateUser();
            $user->save();
            Yii::$app->session->setFlash('success', 'Email anda telah diverifikasi.');
            return $this->redirect(['user/login']);
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Login();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('form/login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequest();
 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
 
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Kami telah kirim email kepada anda, Ikuti perintah di email!.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Maaf, kami tidak dapat mereset password anda.');
            }
 
        }
 
        return $this->render('form/requestPasswordResetToken', [
            'model' => $model,
        ]);
    }
 
    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPassword($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
 
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Password baru telah kami simpan.');
            return $this->goHome();
        }
 
        return $this->render('form/resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Change password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionGantiPassword()
    {
        $model = new GantiPassword();
        if ($model->load(Yii::$app->getRequest()->post()) && $model->change()) {
            return $this->goHome();
        }

        return $this->render('form/gantiPassword', [
                'model' => $model,
        ]);
    }

}
