<?php

	namespace app\models;

	use yii\base\Model;

	/**
	 * Регистрация
	 */
	class SignupForm extends Model
	{
		public $firstname;

		public $middlename;

		public $lastname;

		public $birthday;

		public $sex;

		public $username;

		public $password;

		public $confirm;

		public $updated_at;
		public $verifyCode;

		/**
		 * rules Валидаторы
		 */
		public function rules()
		{
			return
			[
				[['password', 'username', 'firstname', 'middlename', 'birthday', 'sex', 'confirm', 'updated_at'], 'required' ],
				[['password', 'username', 'firstname', 'middlename', 'birthday', 'confirm'], 'trim' ],
				['password', 'string', 'min' => 6],
				['confirm', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают" ],
				[ 'username', 'unique'],
				[ 'verifyCode', 'captcha' ],
			];
		}

		/**
		 * Аттрибуты Labels for SignupForm
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