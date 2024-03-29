<?php

namespace app\controllers;

use app\models\Content;
use app\models\Contentandfoto;
use app\models\Foto;
use app\models\PostForm;
use app\models\SignupForm;
use app\models\User;
use reketaka\comments\widgets\CommentFormWidget;
use Yii;
use yii\data\Pagination;
use yii\db\Query;
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
                'only' => ['logout', 'index', 'about'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'about'],
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
     * Главное окно
     */
    public function actionIndex()
    {
	    $posts = Content::find()->select(['id', 'header', 'alias', 'text_short', 'fk_status', 'mainImage'])
//		                               ->where(['fk_status' => 2]) // Опубликован (Активен)
			                           ->groupBy(["id"])
	                                   ->all();

        return $this->render('index', [
            'posts' => $posts,
        ]);
    }

	/***
	 * Просмотр поста
	 */
	public function actionView($id)
	{
		$model = Content::find()->where(['id' => $id])->one();

		$images = Foto::find()->select(['path_to_foto'])
							  ->innerJoinWith('contentandfoto')
							  ->where(['contentandfoto.fk_content' => $id])
							  ->all();

		return $this->render('view', [
			'images' => $images,
			'model' => $model,
		]);
	}

    /**
     * Авторизация
     */
    public function actionLogin()
    {

        // Если пользователь не гость, то отправляю на главную страницу
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Обновляю пользователю последнюю дату входа
            $username = Yii::$app->request->post("LoginForm")["username"];
            $user = User::findOne(['username' => $username]);
            $user->date_last_login = date("d-m-Y H:i:s");
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

        if ($model->load(Yii::$app->request->post())) {
            $user = new User();
            $user->firstname = Yii::$app->request->post("SignupForm")["firstname"];
            $user->middlename = Yii::$app->request->post("SignupForm")["middlename"];
            $user->lastname = Yii::$app->request->post("SignupForm")["lastname"];
            $user->birthday = Yii::$app->request->post("SignupForm")["birthday"];
            $user->sex = Yii::$app->request->post("SignupForm")["sex"];
            $user->username = Yii::$app->request->post("SignupForm")["username"];

            // Подключаю файл php с массивом
            $params = require '../config/params.php';

            $user->password = md5(Yii::$app->request->post('SignupForm')["password"]) . $params['sol'];
            $user->created_at = date("Y-m-d");
            $user->fk_role = 1;
            $user->status = 10;

            if (!$user->save()) {
                $error = VarDumper::dumpAsString($user->getErrors());
                return $this->render('signup', compact('model', 'error'));
            } else {
                return $this->goBack();
            }

        }

        $error = '';
        return $this->render('signup', compact('model', 'error'));
    }

    /**
     * Контакты
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
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
