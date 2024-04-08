<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view".
 *
 * @property int $id
 * @property int|null $fk_content
 * @property int|null $fk_user
 * @property string|null $date_view
 *
 * @property Content $fkContent
 * @property User $fkUser
 */
class View extends \yii\db\ActiveRecord
{
	public $counts;

    public static function tableName()
    {
        return 'view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_content', 'fk_user'], 'default', 'value' => null],
            [['fk_content', 'fk_user'], 'integer'],
            [['date_view'], 'safe'],
            [['fk_content'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['fk_content' => 'id']],
            [['fk_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['fk_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_content' => 'Fk Content',
            'fk_user' => 'Fk User',
            'date_view' => 'Date View',
        ];
    }

    /**
     * Gets query for [[FkContent]].
     */
    public function getContent()
    {
        return $this->hasOne(Content::className(), ['id' => 'fk_content']);
    }

    /**
     * Gets query for [[FkUser]].
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'fk_user']);
    }
}
