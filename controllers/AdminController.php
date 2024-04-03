<?php

namespace app\controllers;

use app\models\PostForm;
use app\models\Role;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserSearch;
use Yii;
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
     * Создание a new User model.
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost && $model->load($this->request->post())) {

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();


            try {

	            $model->created_at = date("Y-m-d");

	            // Подключаю файл php с массивом
	            $params = require '../config/params.php';
	            $model->password = md5($model->password) . $params['sol'];

	            $model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');

                $query=new Query();
                $idUser= $query->from('user')->orderBy(['id' => SORT_DESC])->one();
                $int = $idUser['id'] + 1;

                // Создаю директорию и физически сохраняю файл
                FileHelper::createDirectory( "avatar/user-{$int}");
                $path = "avatar/user-{$int}/{$model->avatarImage->baseName}.{$model->avatarImage->extension}";

                $model->avatarImage->saveAs($path, false);

                $model->avatar = $path;
	            $model->save();

//                if ($model->save()) {
//                    return $this->redirect(['view', 'id' => $int]);
//                }

                $transaction->commit();

	            return $this->redirect(['view', 'id' => $int]);


            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
            }


        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     *  Обновление User model.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost && $model->load($this->request->post())) {
            // Обновить дату обновления аккаунта
            $model->updated_at = date("Y-m-d");

            // Зашифровать пароль
            $md5 = md5($model->password);

            // Подключаю файл php с массивом
            $params = require '../config/params.php';
            $model->password = $md5 . $params['sol'];

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
		return $this->render('adm',);
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
