<?php

namespace app\models;

use yii\base\Model;

class CategoryForm extends Model
{
    public $category;

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
}