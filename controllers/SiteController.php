<?php

namespace app\controllers;

use app\models\Content;
use app\models\Contentandfoto;
use app\models\Foto;
use app\models\PostForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;

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
	                [
		                'actions' => ['index'],
		                'allow' => true,
		                'roles' => ['?'],
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

	/***
	 *  Удалить свой профиль
	 */
	public function actionDelete()
	{
		$db = Yii::$app->db;
		$transaction = $db->beginTransaction();
		$id = Yii::$app->user->id;
		try {

			$model = User::findOne(['id' => $id]);
			$model->delete();

			// Удаляю директорию со старым фото на чистом PHP
			$path = "avatar/user-{$id}";

			if (is_dir($path)) {
				if(count(scandir($path)) !== 2) {
					unlink($model->avatar);
				}

				rmdir($path);
			}
			Yii::$app->user->logout();

			$transaction->commit();

			return $this->redirect(['index']);

		} catch(\Exception $e) {
			$transaction->rollBack();
			throw $e;
		} catch(\Throwable $e) {
			$transaction->rollBack();
		}

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
     * Личный кабинет
     */
    public function actionAbout()
    {
		$model = User::findOne(['id' => Yii::$app->user->id]);
        return $this->render('about', compact('model'));
    }

	/**
	 * Мои посты в личном кабинете
	 */
	public function actionPosts()
	{
        $dataProvider = new ActiveDataProvider([
            'query' => Content::find()->where(['fk_user_create' => Yii::$app->user->id]),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

		return $this->render('posts', [
                            'dataProvider' => $dataProvider]);
	}

	/***
	 *  Обновление личного профиля
	 */
	public function actionUpdate()
	{
		$model = User::findOne(['id' => Yii::$app->user->id]);

		if ($this->request->isPost && $model->load($this->request->post())) {

			$db = Yii::$app->db;
			$transaction = $db->beginTransaction();

			try {

				// Обновить дату обновления аккаунта
				$model->updated_at = date("Y-m-d");

				// Зашифровать пароль
				$md5 = md5($model->password);

				// Подключаю файл php с массивом
				$params = require '../config/params.php';
				$model->password = $md5 . $params['sol'];

				$model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');

				// Если загружена картинка
				if($model->avatarImage !== null) {

					// Удаляю директорию со старым фото на чистом PHP
					$path = "avatar/user-{$model->id}";

					if (is_dir($path)) {
						if(count(scandir($path)) !== 2) {
							unlink($model->avatar);
						}

						rmdir($path);
					}

					// Создаю директорию и физически сохраняю файл
					FileHelper::createDirectory( "avatar/user-{$model->id}");

					$path = "avatar/user-{$model->id}/{$model->avatarImage->baseName}.{$model->avatarImage->extension}";

					$model->avatarImage->saveAs($path, false);
					$model->avatar = $path;
				} elseif ($model->avatarImage === null && $model->avatar == null) {

					$file = 'avatar/default1.png';
					$newfile = "avatar/user-{$model->id}/default1.png";

					// Создаю директорию и физически сохраняю файл
					FileHelper::createDirectory( "avatar/user-{$model->id}");

					if (!copy($file, $newfile)) {
						echo "failed to copy $file...\n";
					}

					$model->avatar = $newfile;

				} elseif ($model->avatarImage === null && $model->avatar != null) {
					$model->save();

					$transaction->commit();

					return $this->redirect(['about']);
				}

				$model->save();
				$transaction->commit();
				return $this->redirect(['about']);

			} catch(\Exception $e) {
				$transaction->rollBack();
				throw $e;
			} catch(\Throwable $e) {
				$transaction->rollBack();
			}

		}

		return $this->render('update', compact('model'));
	}

	/***
	 * Поиск
	 */
	public function actionSearch()
	{

        $posts = Content::find()->select(['id', 'header', 'alias', 'text_short', 'fk_status', 'mainImage'])
		                        //->where(['fk_status' => 2])// Опубликован (Активен)
                                ->orFilterWhere(['like', 'header', '%'.$_POST['search'].'%', false])
                                ->orFilterWhere(['like', 'text_short', '%'.$_POST['search'].'%', false])
                               ->orFilterWhere(['like', 'text_full', '%'.$_POST['search'].'%', false])
                               ->groupBy(["id"])
                                ->all();

        if($posts != null) {
            return $this->render('index', [
                'posts' => $posts,
            ]);
        }

		$this->actionIndex();
	}

}
