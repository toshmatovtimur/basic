<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "foto".
 *
 * @property int $id
 * @property string|null $name_f
 * @property string|null $path_to_foto
 *
 * @property Contentandfoto[] $contentandfotos
 */
class Foto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'foto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name_f'], 'string', 'max' => 120],
            [['path_to_foto'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_f' => 'Name F',
            'path_to_foto' => 'Path To Foto',
        ];
    }

    /**
     * Gets query for [[Contentandfotos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContentandfotos()
    {
        return $this->hasMany(Contentandfoto::className(), ['fk_foto' => 'id']);
    }
}
