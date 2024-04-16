<?php

	namespace app\models;

	use yii\base\Model;

	class CommentForm extends Model
	{

	public $fk_content;
	public $fk_user;
	public $date_write_comment;
	public $comment;

		/**
		 * @return array the validation rules.
		 */
		public function rules()
		{
			return [
				[['comment'], 'required'],
				['comment', 'safe'],
			];
		}

		/**
		 * @return array customized attribute labels
		 */
		public function attributeLabels()
		{
			return [
				'comment' => 'Комментарий',
			];
		}
	}