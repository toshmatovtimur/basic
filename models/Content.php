<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property string|null $header
 * @property string|null $alias
 * @property string|null $date_create
 * @property string|null $date_publication
 * @property string|null $text_short
 * @property string|null $text_full
 * @property string|null $date_update_content
 * @property string|null $tags
 * @property string|null $mainImage
 * @property int|null $fk_status
 * @property int|null $category_fk
 * @property int|null $fk_user_create
 *
 * @property Comment[] $comments
 * @property Contentandfoto[] $contentandfotos
 * @property Role $fkStatus
 * @property Status $fkUserCreate
 * @property Category $categoryFk
 * @property View[] $views
 */
class Content extends ActiveRecord
{

	/**
	 * @var UploadedFile[]
	 */
	public $imageContent;
    /**
     * Имя таблицы
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * Правила для Контента
     */
    public function rules()
    {
        return [
            [['date_create', 'date_publication', 'date_update_content', 'mainImage', 'category_fk'], 'safe'],
            [['text_full'], 'string'],
            [['fk_status', 'fk_user_create'], 'default', 'value' => null],
            [['fk_status', 'fk_user_create'], 'integer'],
            [['header'], 'string', 'max' => 100],
            [['alias'], 'string', 'max' => 70],
            [['text_short'], 'string', 'max' => 200],
            [['tags'], 'string', 'max' => 150],
	        [['imageContent'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10],
            [['fk_status'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['fk_status' => 'id']],
            [['fk_user_create'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['fk_user_create' => 'id']],
        ];
    }

    /**
     * Лэйблес для Контента
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'header' => 'Заголовок',
            'alias' => 'Алиас',
            'date_create' => 'Дата создания',
            'date_publication' => 'Опубликовано',
            'text_short' => 'Короткий текст',
            'text_full' => 'Полный текст',
            'date_update_content' => 'Дата обновления поста',
            'tags' => 'Тэги',
            'fk_status' => 'Статус',
            'status.status_name' => 'Статус',
            'fk_user_create' => 'Создатель',
            'user.username' => 'Создатель',
            'user' => 'Создатель',
            'mainImage' => 'Картинка',
            'category_fk' => 'Категория',
        ];
    }

    #region Связи с таблицами

    /**
     * Gets query for [[Comments]].
     */
    public function getComment()
    {
        return $this->hasMany(Comment::class, ['fk_content' => 'id']);
    }

    /**
     * Gets query for [[Contentandfotos]].
     */
    public function getContentandfoto()
    {
        return $this->hasMany(Contentandfoto::class, ['fk_content' => 'id']);
    }

    /**
     * Gets query for [[FkStatus]].
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'fk_status']);
    }

    /**
     * Gets query for [[FkUserCreate]].
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'fk_user_create']);
    }

	/**
	 * Gets query for [[category_fk]].
	 */
	public function getCategory()
	{
		return $this->hasOne(Category::class, ['id' => 'category_fk']);
	}

    /**
     * Gets query for [[Views]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getView()
    {
        return $this->hasMany(View::class, ['fk_content' => 'id']);
    }

    #endregion
}
