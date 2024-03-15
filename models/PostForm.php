<?php

namespace app\models;

use yii\base\Model;

class PostForm extends Model
{

//* @property Comment[] $comments
//* @property Contentandfoto[] $contentandfotos
//* @property Role $fkStatus
//* @property Status $fkUserCreate
//* @property View[] $views

    public $header;

    public $alias;
    public $date_create;
    public $date_publication;
    public $text_short;
    public $text_full;
    public $date_update_content;

    public $tags;

    public $status;
    public $user_create;
    public $nameImage;
    public $pathImage;

    /**
     * rules Валидаторы PostForm
     */
    public function rules()
    {
        return
            [
                [['password', 'username', 'firstname', 'middlename'], 'required' ],
                [['password', 'username', 'firstname', 'middlename', 'birthday', 'confirm'], 'trim' ],
                ['password', 'string', 'min' => 6],
                ['confirm', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],
                [ 'username', 'unique'],
                [ 'verifyCode', 'captcha' ],
            ];
    }

    /**
     * Аттрибуты Labels for PostForm
     */
    public function attributeLabels()
    {
        return
            [
                'firstname' => 'Фамилия',
                'middlename' => 'Имя',
                'lastname' => 'Отчество',
                'birthday' => 'Дата рождения',
                'sex' => 'Пол',
                'username' => 'Логин',
                'password' => 'Пароль',
                'confirm' => 'Повторите пароль',
                'verifyCode' => 'Напечатайте слово',
            ];
    }

}