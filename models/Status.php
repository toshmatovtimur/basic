<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property string|null $status_name
 *
 * @property Content[] $contents
 */
class Status extends ActiveRecord
{
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_name'], 'string', 'max' => 70],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_name' => 'Status Name',
        ];
    }

    /**
     * Gets query for [[Contents]].
     */
    public function getContent()
    {
        return $this->hasMany(Content::className(), ['fk_user_create' => 'id']);
    }
}
