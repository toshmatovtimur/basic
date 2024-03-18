<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contentandfoto".
 *
 * @property int $id
 * @property int|null $fk_content
 * @property int|null $fk_foto
 *
 * @property Content $fkContent
 * @property Foto $fkFoto
 */
class Contentandfoto extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'contentandfoto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_content', 'fk_foto'], 'default', 'value' => null],
            [['fk_content', 'fk_foto'], 'integer'],
            [['fk_content'], 'exist', 'skipOnError' => true, 'targetClass' => Content::className(), 'targetAttribute' => ['fk_content' => 'id']],
            [['fk_foto'], 'exist', 'skipOnError' => true, 'targetClass' => Foto::className(), 'targetAttribute' => ['fk_foto' => 'id']],
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
            'fk_foto' => 'Fk Foto',
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
     * Gets query for [[FkFoto]].
     */
    public function getFoto()
    {
        return $this->hasOne(Foto::className(), ['id' => 'fk_foto']);
    }
}
