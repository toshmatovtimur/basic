<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class PostForm extends Model
{

//* @property Comment[] $comments
//* @property Contentandfoto[] $contentandfotos
//* @property Role $fkStatus
//* @property Status $fkUserCreate
//* @property View[] $views

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
    public $nameImage;

	/**
	 * @var UploadedFile
	 */
    public $image;



	/**
     * rules Валидаторы PostForm
     */
    public function rules()
    {
        return
        [
             [['header', 'text_short', 'text_full', 'nameImage'], 'required' ],
             [['text_full', 'header', 'text_short', 'nameImage'], 'trim' ],
	         [['image'], 'file', 'extensions' => 'png, jpg, jpeg'],

        ];
    }

    /**
     * Аттрибуты Labels for PostForm
     */
    public function attributeLabels()
    {
        return
            [
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
            ];
    }

	/**
	 * Загрузка файла
	 */
//	public function upload()
//	{
//		$this->image->saveAs('uploads/' . $this->image->baseName . '.' . $this->image->extension);
//		return true;
////		if ($this->validate())
////		{
////			$this->image->saveAs('uploads/' . $this->image->baseName . '.' . $this->image->extension);
////			return true;
////		}
////		else
////		{
////			return false;
////		}
//	}

}