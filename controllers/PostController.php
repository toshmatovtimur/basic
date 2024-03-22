<?php

namespace app\controllers;

use app\models\Content;
use app\models\Contentandfoto;
use app\models\ContentSearch;
use app\models\Foto;
use app\models\PostForm;
use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PostController implements the CRUD actions for Content model.
 */
class PostController extends Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Content models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ContentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
	    $images = Foto::find()->select(['path_to_foto'])
		    ->innerJoinWith('contentandfoto')
		    ->where(['contentandfoto.fk_content' => $id])
		    ->all();

        return $this->render('view', [
            'model' => $this->findModel($id),
            'images' => $images,
        ]);
    }

	/**
	 * Не нужен пока что, создание поста, есть уже добавление поста
	 */
    public function actionCreate()
    {
        $model = new Content();

        if ($this->request->isPost)
		{
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
		else
		{
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

	/**
	 * Обновление поста
	 */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

	        $db = Yii::$app->getDb();

	        $transaction = $db->beginTransaction();

	        try
	        {
				$model->date_update_content = date("d-m-Y H:i:s");
				$model->imageContent = null;
	            if(!$model->save()) {
					$error = VarDumper::dumpAsString($model->getErrors());
					return $this->render('update', compact('model', 'error',));
				}

		        // Загружаю картинку(и)
		            $model->imageContent = UploadedFile::getInstances($model, 'imageContent');


					#region Удаление фоток из БД
		            $files = [];
		            $foto = Contentandfoto::find()
			                ->select(['fk_foto'])
			                ->where(['contentandfoto.fk_content' => $model->id])
			                ->all();

		            Contentandfoto::deleteAll(['fk_content' => $model->id]);

					foreach ($foto as $item) {
						$fotka = Foto::findOne($item->fk_foto);
						$files[] = $fotka->path_to_foto;
						$fotka->delete();
					}

		            // Удаляю директорию со старыми фото на чистом PHP - не выполняется
		            $path = "img/post-{$model->id}";
		            if(!count(scandir($path))==2) {
			            if($files != null) {
				            foreach ($files as $item) {
					            unlink($item);
				            }
			            }
		            }

					if (is_dir($path)) {
			             rmdir($path);
					}

					#endregion


					// Сохраняю фотки физически в новую старую папку
					// Создаю директорию и физически сохраняю файл
					FileHelper::createDirectory("img/post-{$model->id}");

					// До сюда все хорошо
					foreach ($model->imageContent as $file) {
						$path = "img/post-{$model->id}/{$file->baseName}.{$file->extension}";
						$file->saveAs($path);
					}

					$query=new Query();

					// Вставка в таблицу Foto
					foreach ($model->imageContent as $file) {
						$path = "img/post-{$model->id}/{$file->baseName}.{$file->extension}";
						$foto = new Foto();
						$foto->name_f = "{$file->baseName}.{$file->extension}";
						$foto->path_to_foto = $path;
						if (!$foto->save())
						{
							$error = '';
							return $this->render('upload', compact('model', 'error',));
						}
						else
						{
							$idFoto= $query->from('foto')->orderBy(['id' => SORT_DESC])->one();

							// Вставка в таблицу Contentandfoto
							$contentFoto = new Contentandfoto(); // id-шники сохранять
							$contentFoto->fk_foto = $idFoto['id'];
							$contentFoto->fk_content = $model->id;

							if (!$contentFoto->save()) {
								$error = VarDumper::dumpAsString($contentFoto->getErrors());
								return $this->render('upload', compact('model', 'error',));
							}
						}
					}


		        $transaction->commit();
	        }
	        catch (\Exception $e)
	        {
		        $transaction->rollBack();
	        }
	        catch (\Throwable $e)
	        {
		        $transaction->rollBack();
	        }

            return $this->redirect(['view', 'id' => $model->id]);
        }


		$error = '';
        return $this->render('update', compact('model', 'error',));
    }

	/**
     * Загрузка Контента
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


	        $db = Yii::$app->getDb();

	        $transaction = $db->beginTransaction();

	        try
	        {

		        if (!$content->save()) {
			        $error = VarDumper::dumpAsString($content->getErrors());
			        return $this->render('upload', compact('model', 'error'));
		        }

		        // Загружаю картинку и получаю id последней записи в таблице Content
		        $model->image = UploadedFile::getInstances($model, 'image');

		        $model->upload(); // Загружаю файл(ы)

		        // Получаю id последнего загруженного поста
		        $query=new Query();
		        $idContent= $query->from('content')->orderBy(['id' => SORT_DESC])->one();

		        // Вставка в таблицу Foto
		        foreach ($model->image as $file) {
			        $path = "img/post-{$idContent['id']}/{$file->baseName}.{$file->extension}";
			        $foto = new Foto();
			        $foto->name_f = "{$file->baseName}.{$file->extension}";
			        $foto->path_to_foto = $path;
			        if (!$foto->save())
			        {
				        $error = VarDumper::dumpAsString($foto->getErrors());
				        return $this->render('upload', compact('model', 'error'));
			        }
			        else
			        {
				        $idFoto= $query->from('foto')->orderBy(['id' => SORT_DESC])->one();

				        // Вставка в таблицу Contentandfoto
				        $contentFoto = new Contentandfoto(); // id-шники сохранять
				        $contentFoto->fk_foto = $idFoto['id'];
				        $contentFoto->fk_content = $idContent['id'];

				        if (!$contentFoto->save()) {
					        $error = VarDumper::dumpAsString($contentFoto->getErrors());
					        return $this->render('upload', compact('model', 'error'));
				        }
			        }
		        }


		        $transaction->commit();
	        }
			catch (\Exception $e)
			{
		        $transaction->rollBack();
	        }
			catch (\Throwable $e)
			{
		        $transaction->rollBack();
	        }

        }

        $error = '';
        return $this->render('upload', compact('model', 'error'));
    }
    /**
     * Удалить контент
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Content::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
