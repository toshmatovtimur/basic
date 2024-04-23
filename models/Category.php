<?php

	namespace app\models;

	use yii\db\ActiveRecord;

	/**
	 * This is the model class for table "role".
	 *
	 * @property int $id
	 * @property string|null $category
	 *
	 */

	class Category extends ActiveRecord
	{
		public static function tableName()
		{
			return 'category';
		}

		/**
		 * {@inheritdoc}
		 */
		public function rules()
		{
			return [
				[['category'], 'string', 'max' => 255],
			];
		}

		public function attributeLabels()
		{
			return [
				'category' => 'Категория',
			];
		}

		/**
		 * Gets query for [[Contents]].
		 */
		public function getContent()
		{
			return $this->hasMany(Content::class, ['category_fk' => 'id']);
		}
	}