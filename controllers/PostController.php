<?php

namespace app\controllers;

use app\models\Content;
use app\models\Contentandfoto;
use app\models\Foto;
use app\models\PostForm;
use app\models\UserIdentity;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class PostController extends Controller
{

    /**
     * Правила для Контроллера
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return UserIdentity::isAdmin();
                        }
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
     * index for PostController
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * upload action / Добавить пост
     */
    public function actionUpload()
    {
        $model = new PostForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->date_create = date("d-m-Y H:i:s");
            $model->fk_user_create = Yii::$app->user->id;
            $model->image = UploadedFile::getInstance($model, 'image');

            // Надо брать id last posta и + 1
            // То есть делать запрос в БД
            // И если такой директории нет, чтоб не перезаписать случайно, то создаю новую и туда все сохраняю
            $number = 2;

            // Создаю директорию и физически сохраняю файл
            FileHelper::createDirectory("img/post-{$number}");
            $path = "img/post-{$number}/{$model->image->baseName}.{$model->image->extension}";
            $model->image->saveAs($path);

            // Создание трех таблиц
            $content = new Content();
            $contentFoto = new Contentandfoto(); // id-шники сохранять
            $foto = new Foto(); // foto


            //$content->load()


            return $this->render('upload', [
                'model' => $model,
            ]);
        }


        return $this->render('upload', [
            'model' => $model,
        ]);
    }
}