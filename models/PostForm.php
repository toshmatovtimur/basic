<?php

namespace app\models;

use yii\base\Exception;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class PostForm extends Model
{
    public $header; // Заголовок
    public $alias; // Алиас - запоминающееся короткое имя
    public $date_create; // Дата создания - автомат
    public $date_publication; // Дата публикации - автомат
    public $text_short; // короткий текст отражающий суть поста
    public $text_full; // Полный текст поста
    public $date_update_content; // Дата обновления поста - автомат
    public $tags; // Посты на определенные темы

    public $fk_status; // Автомат
    public $fk_user_create;
    public $category_fk;
    public $nameImage;

	/**
	 * @var UploadedFile[]
	 */
    public $image;

	/**
     * rules Валидаторы PostForm
     */
    public function rules()
    {
        return
        [
             [['header', 'text_short', 'text_full', 'nameImage', 'image'], 'required' ],
             [['tags', 'category_fk'], 'safe' ],
             [['text_full', 'header', 'text_short', 'nameImage'], 'trim' ],
	         [['image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
        ];
    }

    /**
     * Аттрибуты Labels for PostForm
     */
    public function attributeLabels()
    {
        return [
                'header' => 'Заголовок',
                'alias' => 'Алиас',
                'date_create' => 'Дата создания',
                'date_publication' => 'Дата публикации',
                'text_short' => 'Короткий текст',
                'text_full' => 'Текст',
                'date_update_content' => 'Дата обновления контента',
                'tags' => 'Тэги',
                'status' => 'Статус',
                'user_create' => 'Создатель',
                'image' => 'Картинка',
                'category_fk' => 'Категория',
            ];
    }

    /**
     * @throws Exception
     */
    public function upload()
	{
		$query=new Query();
		$idContent= $query->from('content')->orderBy(['id' => SORT_DESC])->one();

		// Создаю директорию и физически сохраняю файл
		FileHelper::createDirectory("img/post-{$idContent['id']}");

			foreach ($this->image as $file) {
				$path = "img/post-{$idContent['id']}/{$file->baseName}.{$file->extension}";
				$file->saveAs($path);
			}

	}
}