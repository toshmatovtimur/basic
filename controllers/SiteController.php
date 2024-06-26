<?php

namespace app\controllers;

use app\models\Comment;
use app\models\CommentForm;
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
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\httpclient\Client;
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
                'only' => ['logout', 'index', 'about', 'identity'],
                'rules' => [
                    [
                        'actions' => ['logout', 'index', 'about', 'identity'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
	                [
		                'actions' => ['index', 'identity'],
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
	 * Авторизация
	 */
	public function actionLogin()
	{
		// Если пользователь не гость, то отправляю на главную страницу
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		// Формирую URL строку
		$url = 'https://oauth.tpu.ru/authorize?' . http_build_query(['client_id' => 58, 'redirect_uri' => 'http://basic.loc/site/token', 'client_secret' => 'rFt1B5i1', 'response_type' => 'code', 'state' => '1234']);
		return $this->redirect($url);
    }

	public function actionToken()
	{
		if(Yii::$app->request->isGet) {
            // Формирую URL строку
			$state = Yii::$app->request->get('state');
            $url = "https://oauth.tpu.ru/authorize?client_id=58&redirect_uri=http://basic.loc/site/identity&response_type=code&state={$state}";
			return $this->redirect($url);
		}
	}

	public function actionIdentity()
	{
		// Еще один редирект нужно получить token
		// А потом получу json
        if(Yii::$app->request->isGet) {
            $code = Yii::$app->request->get('code');

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl('https://oauth.tpu.ru/access_token')
                ->setData(['client_id' => 58, 'client_secret' => 'rFt1B5i1', 'code' => $code, 'grant_type' => 'authorization_code'])
                ->send();

            if ($response->isOk) {
                // успешный запрос
                $responseData = $response->data;

                $responseInfo = $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl('https://api.tpu.ru/v2/auth/user')
                    ->setData(['apiKey' => '567ed07468465ffea45ce4916f8ac9be', 'access_token' => $responseData['access_token']])
                    ->send();

                    if($responseInfo->isOk) {
                        $responseUser = $responseInfo->data;

                        $id = $responseUser['user_id'];
                        $email = $responseUser['email'];
                        $family = $responseUser['lichnost']['familiya'];
                        $name = $responseUser['lichnost']['imya'];
                        $lastname = $responseUser['lichnost']['otchestvo'];

                        $user = User::findOne(['tpuId'=> $id]);
                        if($user != null) {
                            // Если такой пользователь в моей локальной базе существует, то логиню его
                            $model = new LoginForm();
                            $model->username = $user->username;
                            $model->password = $user->password;
                            $model->login();

                            $user->date_last_login = date("d-m-Y H:i:s");
                            $user->save();
                            return $this->goHome();

                        } elseif($user == null) {
                            // Иначе, если в базе нет такого usera то регистрирую его
                            // Создаю новый объект User
                            $user = new User();
                            $user->firstname = $family;
                            $user->middlename = $name;
                            $user->lastname = $lastname;
                            $user->tpuId = $id;
                            $user->birthday = null;
                            $user->sex = null;
                            $user->username = $email;
                            $user->password = md5($email . Yii::$app->params['sol']);
                            $user->created_at = date("Y-m-d");
                            $user->fk_role = 1;
                            $user->status = 10;

                            if($user->save()) {
                                // Получаю id последнего пользователя
                                $query=new Query();
                                $idUser= $query->from('user')->orderBy(['id' => SORT_DESC])->one();

                                $user = User::findOne(['id'=> $idUser['id']]);
                                if($user != null) {
                                    // Если такой пользователь в моей локальной базе существует, то логиню его
                                    $model = new LoginForm();
                                    $model->username = $user->username;
                                    $model->password = $user->password;
                                    $model->login();

                                    $user->date_last_login = date("d-m-Y H:i:s");
                                    $user->save();
                                    return $this->goHome();
                                }
                            }
                        }
                    }
            }
        }
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
            // Создаю новый объект User
			$user = new User();
			$user->firstname = $model->firstname;
			$user->middlename = $model->middlename;
			$user->lastname = $model->lastname;
			$user->birthday = $model->birthday;
			$user->sex = $model->sex;
			$user->username = $model->username;
			$user->password = md5($model->password . Yii::$app->params['sol']);
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
	#region Главное окно, просмотр постов и прочее

	/**
	 * Главное окно
	 */
	public function actionIndex()
	{
		$query = Content::find()->select(['id', 'header', 'alias', 'text_short', 'fk_status', 'mainImage']);

		$pages = new Pagination([
			'totalCount' => $query->count(),
			'defaultPageSize' => 5, // количество элементов на странице
		]);
		$posts = $query->offset($pages->offset)
					   ->limit($pages->limit)
			           ->all();

		return $this->render('index', [
			'posts' => $posts,
			'pages' => $pages,
		]);
	}

	/***
	 *  Главное окно по категориям
	 */
	public function actionGetCategory($id)
	{
		$query = Content::find()->select(['id', 'header', 'alias', 'text_short', 'fk_status', 'mainImage'])
				                ->where(['category_fk' => $id]);

		$countQuery = clone $query;
		$pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'defaultPageSize' => 5, // количество элементов на странице
        ]);
		$posts = $query->offset($pages->offset)
			->limit($pages->limit)
			->all();

		return $this->render('index', [
			'posts' => $posts,
			'pages' => $pages,
		]);
	}

	/***
	 * Просмотр поста
	 */
	public function actionView($id)
	{
		$commentForm = new CommentForm();

		if(Yii::$app->request->isPost && $commentForm->load(Yii::$app->request->post())
			                          && $commentForm->validate())
        {
			$comment = new Comment();
			$comment->fk_user = Yii::$app->user->id;
			$comment->fk_content = $id;
			$comment->date_write_comment = date("d-m-Y H:i:s");
			$comment->comment = $commentForm->comment;

			$db = Yii::$app->db;
			$transaction = $db->beginTransaction();

			try {
				$comment->save();
				$transaction->commit();
				Yii::$app->session->setFlash('success', "Комментарий успешно добавился");

			} catch(\Exception $e) {
				$transaction->rollBack();
				throw $e;
			} catch(\Throwable $e) {
				$transaction->rollBack();
			}
			
			return $this->redirect(['site/hello', 'id' => $id]);
		}

		$model = Content::find()->where(['id' => $id])->one();

		$commentContent = Comment::find()->where(['fk_content' => $id])->all();

		$images = Foto::find()->select(['path_to_foto'])
							  ->innerJoinWith('contentandfoto')
							  ->where(['contentandfoto.fk_content' => $id])
							  ->all();

		return $this->render('view', [
					 'images' => $images,
					 'model' => $model,
					 'commentForm' => $commentForm,
					 'commentContent' => $commentContent,
		]);
	}

	/***
	 * Поиск
	 */
	public function actionSearch()
	{
        $query = Content::find()->select(['id', 'header', 'alias', 'text_short', 'fk_status', 'mainImage'])
			//->where(['fk_status' => 2])// Опубликован (Активен)
			->orFilterWhere(['like', 'header', '%'.$_POST['search'].'%', false])
			->orFilterWhere(['like', 'text_short', '%'.$_POST['search'].'%', false])
			->orFilterWhere(['like', 'text_full', '%'.$_POST['search'].'%', false])
			->groupBy(["id"]);

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => 5, // количество элементов на странице
        ]);
        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

		if($posts != null) {
			return $this->render('index', [
				'posts' => $posts,
				'pages' => $pages,
			]);
		}

		return $this->actionIndex();
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

	/***
	 * Метод нужен чтоб не было глюков при добавлении комментария
	 */
	public function actionHello($id)
	{
		return $this->redirect(['site/view', 'id' => $id]);
	}
	#endregion

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
			}
			catch(\Throwable $e)
			{
				$transaction->rollBack();
			}

		}

		return $this->render('update', compact('model'));
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

}
