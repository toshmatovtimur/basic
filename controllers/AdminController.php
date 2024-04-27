<?php

namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
use app\models\Comment;
use app\models\Content;
use app\models\PostForm;
use app\models\Role;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserSearch;
use app\models\View;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
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
     * Список всех пользователей
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
     * Просмотр профиля пользователя
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

            try {
				// Дата создания
	            $model->created_at = date("Y-m-d");

				// Пароль и соль в MD5
	            $model->password = md5($model->password.Yii::$app->params['sol']);

				// Загрузка аватарки
	            $model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');

				// Сохранение изменений
                $model->save();

				// Получаю id последнего usera
                $query=new Query();
                $idUser= $query->from('user')->orderBy(['id' => SORT_DESC])->one();
                $int = $idUser['id'];

                // Создаю директорию и физически сохраняю файл
                FileHelper::createDirectory( "avatar/user-{$int}");

				// Если аватарка не выбрана, то будет установлена аватарка по умолчанию
                if($model->avatarImage === null) {
					// Аватарка по умолчанию
                    $file = 'avatar/default1.png';

	                // Аватарка по умолчанию в новом пути
                    $newfile = "avatar/user-{$int}/default1.png";

					// Копирование аватарки по умолчанию в новый путь (новую папку)
                    if (!copy($file, $newfile)) {
                        echo "failed to copy $file...\n";
                    }

					// Добавляю аватарку в профиль
                    $image = User::findOne(['id' => $int]);
                    $image->avatar = $newfile;
                    $image->save();

					// Иначе, если аватарка новая выбрана
                } else {
	                // Добавляю аватарку в профиль
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
     *  Обновление данных пользователя
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

		        // Обновить пароль
		        $model->password = md5($model->password.Yii::$app->params['sol']);

				// Загрузка картинки
		        $model->avatarImage = UploadedFile::getInstance($model, 'avatarImage');

				// Если загружена картинка
				if($model->avatarImage !== null) {

					// Удаляю директорию со старым фото на чистом PHP
					$path = "avatar/user-{$model->id}";

					// Если папка существует
					if (is_dir($path)) {
						// Если папка не пустая
						if(count(scandir($path)) !== 2) {
							// Удаляю старую аватарку
							unlink($model->avatar);
						}

						// Удаляю папку
						rmdir($path);
					}

					// Создаю директорию и физически сохраняю файл
					FileHelper::createDirectory( "avatar/user-{$model->id}");

					$path = "avatar/user-{$model->id}/{$model->avatarImage->baseName}.{$model->avatarImage->extension}";

					$model->avatarImage->saveAs($path, false);
					$model->avatar = $path;

					// Иначе если, картинка не загружена и аватарки вдруг нету
				} elseif ($model->avatarImage === null && $model->avatar == null) {

					// Вставляю аватарку по умолчанию
                    $file = 'avatar/default1.png';
                    $newfile = "avatar/user-{$model->id}/default1.png";

                    // Создаю директорию
                    FileHelper::createDirectory( "avatar/user-{$model->id}");

					// Копирую аватарку в новую папку
                    if (!copy($file, $newfile)) {
                        echo "failed to copy $file...\n";
                    }

                    $model->avatar = $newfile;

					// Иначе если, картинка не загружена, а аватарка старая есть
                } elseif ($model->avatarImage == null && $model->avatar != null) {

					// Сохранить изменения
					$model->save();

					// Завершить транзакцию
					$transaction->commit();

					// Редирект на страницу просмотра профиля usera
					return $this->redirect(['view', 'id' => $model->id]);
				}

		        // Сохранить изменения
		        $model->save();

		        // Завершить транзакцию
		        $transaction->commit();

		        // Редирект на страницу просмотра профиля usera
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

			// Если сущ дир по данному пути
		    if (is_dir($path)) {
				// Если дир не пустая
			    if(count(scandir($path)) !== 2) {
					// Кдаляю файл
				    unlink($this->avatar);
			    }

				// После удаляю папку
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

		// Заданная дата, например, 30 дней назад от текущей даты
		$thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));

		//топ-10 страниц, текст которых обновлялся более 1-месяца назад
		$subQuery = Content::find()->select('id')->where(['<', 'date_update_content', $thirtyDaysAgo]);

		$mouthUpdateProvider = new ActiveDataProvider([
			'query' => View::find()->select(['fk_content', 'COUNT(fk_content) as counts'])
				->where(['in', 'fk_content', $subQuery])
				->groupBy(['fk_content'])
				->orderBy( ['counts' => SORT_DESC])
				->limit(10),
		]);

		// Определение дат начала и конца недели
		$weekStart = new Expression("DATE_TRUNC('week', NOW())");
		$weekEnd = new Expression("DATE_TRUNC('week', NOW()) + INTERVAL '1 week' - INTERVAL '1 day'");

		//топ-10 активных пользователей (больше всего комментариев за последнюю неделю)
		$topActiveUsers = new ActiveDataProvider([
			'query' => Comment::find()->select(['fk_user', 'COUNT(comment) as counts',])
				->where(['>=', 'date_write_comment', $weekStart])
				->andWhere(['<=', 'date_write_comment', $weekEnd])
				->groupBy(['fk_user'])
				->orderBy( ['counts' => SORT_DESC])
				->limit(10),
		]);

		return $this->render('statistics', [
			'topProvider' => $topProvider,
			'lastCreateProvider' => $lastCreateProvider,
			'mouthUpdateProvider' => $mouthUpdateProvider,
			'topActiveUsers' => $topActiveUsers,
		]);
	}

    /***
     * Модуль категории
     */
    public function actionCategory()
    {
        $model = new ActiveDataProvider([
            'query' => Category::find()->orderBy( ['id' => SORT_ASC])
        ]);

        return $this->render('category', [
            'model' => $model,
        ]);
    }

    /***
     * @param $id
     * Удалить категорию
     */
    public function actionDeleteCategory($id)
    {
		// Удалить категорию по $id
        Category::deleteAll(['id' => $id]);

		// Вывожу флеш сообщение
	    Yii::$app->session->setFlash('success', 'Запись успешно удалена.');
		return $this->actionCategory();
    }

	/***
	 * Обновить категорию
	 */
	public function actionCategoryUpdate($id)
	{
		// Получаю id категории
		$model = Category::findOne(['id' => $id]);

		if ($model->load(Yii::$app->request->post())) {

			// Если поле не пустое
			if (!preg_match('/^[\s\t]*$/', $model->category)) {

				// Обновляю категорию
				$category = new Category();
				$category->category = trim($model->category);
				$category->save();

				// Вывожу сообщение о успехе!
				Yii::$app->session->setFlash('success', 'Успешно');

				// Редирект на список категорий
				return $this->redirect(['admin/category']);

				// Иначе если поле пустое или содержит пробелы, табы
			} elseif (preg_match('/^[\s\t]*$/', $model->category)) {

				// Вывожу сообщение о ошибке
				Yii::$app->session->setFlash('error', 'Пустое поле недопустимо!');
			}
		}

		return $this->render('categoryUpdate', [
			'model' => $model
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