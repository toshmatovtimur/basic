<?php

namespace app\models;

use yii\web\IdentityInterface;

class UserIdentity extends User implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return static::findOne($id) ;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Вернет admin если пользователь Админ, иначе user;
     */
    public static function isAdmin()
    {
        if(!\Yii::$app->user->isGuest)
        {
            if(\Yii::$app->user->identity->fk_role === 2)
            {
                return true;
            }
        }

        return false;
    }

    public static function findByUsername($username)
    {
	    return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        // Подключаю файл php с массивом
        $params = require '../config/params.php';

        return $this->password === md5($password) . $params['sol'];
    }

}
