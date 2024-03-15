<?php

namespace app\controllers;

use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
	/**
	 * Правила SiteController
	 */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'index'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

	/**
	 * actions SiteController
	 */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

	/**
	 * go homePage-index-SiteController
	 */
    public function actionIndex()
    {
        return $this->render('index');
    }

	/**
	 * Авторизация
	 */
    public function actionLogin()
    {
		// Если пользователь не гость, то отправляю на главную страницу
        if (!Yii::$app->user->isGuest)
		{
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
			// Обновляю пользователю последнюю дату входа
	        $username = Yii::$app->request->post("LoginForm")["username"];
			$user = User::findOne(['username' => $username]);
			$user->date_last_login = date("Y-m-d H:i:s");
			$user->save();

            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

	/**
	 * Выход
	 */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

	/**
	 * action Регистрация
	 */
	public function actionSignup()
	{
		$model = new SignupForm();

		if($model->load(Yii::$app->request->post()))
		{
			$user = new User();
			$user->firstname = Yii::$app->request->post("SignupForm")["firstname"];
			$user->middlename = Yii::$app->request->post("SignupForm")["middlename"];
			$user->lastname = Yii::$app->request->post("SignupForm")["lastname"];
			$user->birthday = Yii::$app->request->post("SignupForm")["birthday"];
			$user->sex = Yii::$app->request->post("SignupForm")["sex"];
			$user->username = Yii::$app->request->post("SignupForm")["username"];
			$user->password = md5(Yii::$app->request->post('SignupForm')["password"]);
			$user->created_at = date("Y-m-d");
			$user->fk_role = 1;
			$user->status = 10;

			if (!$user->save())
			{
				$error = VarDumper::dumpAsString($user->getErrors());
				return $this->render('signup', compact('model', 'error'));
			}
			else
			{
				return $this->goBack();
			}

		}

		return $this->render('signup', compact('model'));
	}

	/**
	 * Контакты
	 */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) 
        {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

	/**
	 * О нас
	 */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
