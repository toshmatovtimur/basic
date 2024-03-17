<?php

namespace app\controllers;

use app\models\Content;
use app\models\Contentandfoto;
use app\models\Foto;
use app\models\PostForm;
use app\models\UserIdentity;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
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

        // Получаю post запрос, если он есть
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // Вставка в таблицу Content
            $content = new Content();
            $content->header = Yii::$app->request->post("PostForm")["header"];
            $content->alias = Yii::$app->request->post("PostForm")["alias"];
            $content->date_create = date("d-m-Y H:i:s");
            $content->text_short = Yii::$app->request->post("PostForm")["text_short"];
            $content->text_full = Yii::$app->request->post("PostForm")["text_full"];
            $content->tags = Yii::$app->request->post("PostForm")["tags"];
            $content->fk_status = 1; // Загружен
            $content->fk_user_create = Yii::$app->user->id;

            if (!$content->save()) {
                $error = VarDumper::dumpAsString($content->getErrors());
                return $this->render('upload', compact('model', 'error'));
            }

            // Загружаю картинку и получаю id последней записи в таблице Content
            $model->image = UploadedFile::getInstance($model, 'image');

            $query=new Query();
            $idContent= $query->from('content')->orderBy(['id' => SORT_DESC])->one();

            // Создаю директорию и физически сохраняю файл
            FileHelper::createDirectory("img/post-{$idContent['id']}");
            $path = "img/post-{$idContent['id']}/{$model->image->baseName}.{$model->image->extension}";
            $model->image->saveAs($path);

            // Вставка в таблицу Foto
            $foto = new Foto();
            $foto->name_f = "{$model->image->baseName}.{$model->image->extension}";
            $foto->path_to_foto = $path;

            if (!$foto->save()) {
                $error = VarDumper::dumpAsString($content->getErrors());
                return $this->render('upload', compact('model', 'error'));
            }

            $idFoto= $query->from('foto')->orderBy(['id' => SORT_DESC])->one();

            // Вставка в таблицу Contentandfoto
            $contentFoto = new Contentandfoto(); // id-шники сохранять
            $contentFoto->fk_foto = $idFoto['id'];
            $contentFoto->fk_content = $idContent['id'];

            if (!$contentFoto->save()) {
                $error = VarDumper::dumpAsString($content->getErrors());
                return $this->render('upload', compact('model', 'error'));
            }

            $error = 'Все загружено';
            return $this->render('upload', compact('model', 'error'));
        }

        $error = '';
        return $this->render('upload', compact('model', 'error'));
    }
}