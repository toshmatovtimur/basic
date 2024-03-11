<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $firstname
 * @property string|null $middlename
 * @property string|null $lastname
 * @property string|null $birthday
 * @property string|null $sex
 * @property string|null $username
 * @property string|null $password
 * @property string|null $date_last_login
 * @property int|null $fk_role
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status
 * @property string|null $access_token
 * @property string|null $auth_key
 *
 * @property Comment[] $comments
 * @property Role $fkRole
 * @property View[] $views
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['birthday', 'date_last_login', 'created_at', 'updated_at'], 'safe'],
            [['sex', 'auth_key'], 'string'],
            [['fk_role', 'status'], 'default', 'value' => null],
            [['fk_role', 'status'], 'integer'],
            [['firstname', 'middlename', 'lastname', 'password', 'access_token'], 'string', 'max' => 120],
            [['username'], 'string', 'max' => 60],
            [['fk_role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['fk_role' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'middlename' => 'Middlename',
            'lastname' => 'Lastname',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'username' => 'Username',
            'password' => 'Password',
            'date_last_login' => 'Date Last Login',
            'fk_role' => 'Fk Role',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['fk_user' => 'id']);
    }

    /**
     * Gets query for [[FkRole]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'fk_role']);
    }

    /**
     * Gets query for [[Views]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViews()
    {
        return $this->hasMany(View::className(), ['fk_user' => 'id']);
    }
}
