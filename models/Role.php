<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string|null $role_user
 *
 * @property Content[] $contents
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_user'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_user' => 'Role User',
        ];
    }

    /**
     * Gets query for [[Contents]].
     */
    public function getContent()
    {
        return $this->hasMany(Content::className(), ['fk_status' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     */
    public function getUser()
    {
        return $this->hasMany(User::className(), ['fk_role' => 'id']);
    }
}
