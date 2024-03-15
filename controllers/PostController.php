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
                        'matchCallback' => function($rule, $action)
                        {
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

		if ($model->load(Yii::$app->request->post())) {
			$model->image = UploadedFile::getInstance($model, 'image');
			$model->image->saveAs("img/imagesPosts/{$model->image->baseName}.{$model->image->extension}");

			// Создание трех таблиц
			$content = new Content();
			$contentFoto = new Contentandfoto();
			$foto = new Foto();


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