<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

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

 * @property Comment[] $comments
 * @property Role $fkRole
 * @property View[] $views
 */
class User extends ActiveRecord
{

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['birthday', 'date_last_login', 'created_at', 'updated_at'], 'safe'],
            [['sex', 'auth_key'], 'string'],
            [['fk_role', 'status'], 'default', 'value' => null],
            [['fk_role', 'status'], 'integer'],
            [['firstname', 'middlename', 'lastname', 'password', 'access_token'], 'string', 'max' => 120],
            [['username'], 'string', 'max' => 60],
            [['username'], 'unique'],
            [['fk_role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['fk_role' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Код',
            'firstname' => 'Фамилия',
            'middlename' => 'Имя',
            'lastname' => 'Отчество',
            'birthday' => 'Дата рождения',
            'sex' => 'Пол',
            'username' => 'Логин',
            'password' => 'Пароль',
            'date_last_login' => 'Последний вход',
            'fk_role' => 'Роль',
            'role.role_user' => 'Роль',
            'role' => 'Роль',
            'created_at' => 'Дата создания аккаунта',
            'updated_at' => 'Дата обновления аккаунта',
            'status' => 'Статус',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
        ];
    }

    #region Связи с таблицами

    /**
     * Gets query for [[Comments]].
     */
    public function getComment()
    {
        return $this->hasMany(Comment::className(), ['fk_user' => 'id']);
    }

    /**
     * Gets query for [[FkRole]].
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'fk_role']);
    }

    /**
     * Gets query for [[Views]].
     */
    public function getView()
    {
        return $this->hasMany(View::className(), ['fk_user' => 'id']);
    }

    #endregion
}
