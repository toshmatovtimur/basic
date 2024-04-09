<?php

namespace app\controllers;

use app\models\Content;
use app\models\PostForm;
use app\models\Role;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserSearch;
use app\models\View;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AdminController implements the CRUD actions for User model.
 */
class AdminController extends Controller
{

    /**
     * Правила для Контроллера
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'index', 'update', 'view', 'test'],
                'rules' => [
                    [
                        'actions' => ['create', 'index', 'update', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return UserIdentity::isAdmin();
                        }
                    ],
                    [
                        'actions' => ['test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
	                [
		                'actions' => ['test'],
		                'allow' => true,
		                'roles' => ['?'],
	                ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Добавить пользователя
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost && $model->load($this->request->post())) {

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();

            try
            {

	            $model->created_at = date("Y-m-d");

	            // Подключаю файл php с массивом
	            $params = require '../config/params.php';
	            $model->password = md5($model->password) . $params['sol'];

	            $model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');

                $model->save();

                $query=new Query();
                $idUser= $query->from('user')->orderBy(['id' => SORT_DESC])->one();
                $int = $idUser['id'];

                // Создаю директорию и физически сохраняю файл
                FileHelper::createDirectory( "avatar/user-{$int}");

                if($model->avatarImage === null) {

                    $file = 'avatar/default1.png';
                    $newfile = "avatar/user-{$int}/default1.png";

                    if (!copy($file, $newfile)) {
                        echo "failed to copy $file...\n";
                    }

                    $image = User::findOne(['id' => $int]);
                    $image->avatar = $newfile;
                    $image->save();


                }
                else
                {
                    $image = User::findOne(['id' => $int]);
                    $path = "avatar/user-{$int}/{$model->avatarImage->baseName}.{$model->avatarImage->extension}";
                    $model->avatarImage->saveAs($path, false);

                    $image->avatar = $path;
                    $image->save();
                }

                $transaction->commit();

            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
            }

	        $idUser= $query->from('user')->orderBy(['id' => SORT_DESC])->one();
	        $int = $idUser['id'];
	        return $this->redirect(['view', 'id' => $int]);

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     *  Обновление пользователя
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
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

					return $this->redirect(['view', 'id' => $model->id]);
				}


		        $model->save();
		        $transaction->commit();

		        return $this->redirect(['view', 'id' => $model->id]);

	        } catch(\Exception $e) {
		        $transaction->rollBack();
		        throw $e;
	        } catch(\Throwable $e) {
		        $transaction->rollBack();
	        }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     *  Запрос на удаление записи по id
     */
    public function actionDelete($id)
    {
	    $db = Yii::$app->db;
	    $transaction = $db->beginTransaction();

	    try {

		    $this->findModel($id)->delete();

		    // Удаляю директорию со старым фото на чистом PHP
		    $path = "avatar/user-{$this->id}";

		    if (is_dir($path)) {
			    if(count(scandir($path)) !== 2) {
				    unlink($this->avatar);
			    }

			    rmdir($path);
		    }

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
     * Тестовый
     */
    public function actionTest()
    {
        //$model = UserIdentity::isAdmin(Yii::$app->user->id);
        return $this->render('test');
    }

	/***
	 * Админка
	 */
	public function actionAdm()
	{
		return $this->render('adm');
	}

	/***
	 * Модуль статистики
	 */
	public function actionStatistics()
	{
		// Топ 10 проматриваемых страниц
		$topProvider = new ActiveDataProvider([
			'query' => View::find()->select(['fk_content', 'COUNT(fk_content) as counts'])
								   ->groupBy(['fk_content'])
								   ->orderBy( ['counts' => SORT_DESC])
								   ->limit(10),
		]);

		// 10 последних созданных страниц
		$lastCreateProvider = new ActiveDataProvider([
			'query' => Content::find()->select(['header', 'date_create'])
									  ->orderBy( ['date_create' => SORT_DESC])
									  ->limit(10),
		]);

		// Вычитание дат
//		$dateNow = new DateTime();
//		$date2 = new DateTime("2009-06-26");
//		$interval = $date1->diff($date2);
//		echo "days difference ".$interval->d." days ";
//
//		$subQuery = Content::find()->select('id')->where(['date_update_content' < date('d-m-Y H:i:s')]);
//		$query = BaseTwitter::find()->where(['not in', 'id', $subQuery]);
//		$models = $query->all();


		// Топ-10 страниц, текст которых обновлялся более 1-месяца назад
//		$mouthUpdateProvider = new ActiveDataProvider([
//			'query' => Content::find()->select(['header', 'date_create', 'COUNT(fk_content) as counts',])
//								      ->where('dateup' != null)
//								      ->groupBy(['fk_content'])
//								      ->orderBy( ['counts' => SORT_DESC])
//								      ->limit(10),
//		]);

		return $this->render('statistics', [
			'topProvider' => $topProvider,
			'lastCreateProvider' => $lastCreateProvider,
//			'mouthUpdateProvider' => $mouthUpdateProvider,
		]);

	}

    /**
     *  Запрос на получение записи по id
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
