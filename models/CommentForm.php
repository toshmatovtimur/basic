<?php

	namespace app\models;

	use yii\base\Model;

	class CommentForm extends Model
	{

	public $fk_content;
	public $fk_user;
	public $date_write_comment;
	public $comment;
	public $captcha;

		/**
		 * @return array the validation rules.
		 */
		public function rules()
		{
			return [
				[['comment'], 'required'],
				[['comment', 'fk_user', 'fk_content'], 'safe'],
				['date_write_comment', 'date', 'format' => 'php:Y-m-d H:i:s'],
				['captcha', 'captcha'],
				['captcha', 'captcha', 'message' => 'Неправильно введена капча.'],
			];
		}

		/**
		 * @return array customized attribute labels
		 */
		public function attributeLabels()
		{
			return [
				'date_write_comment' => 'Дата написания комментария',
				'comment' => 'Комментарии',
				'captcha' => 'Введите слово: ',
			];
		}
	}