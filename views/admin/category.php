<?php

use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

	Pjax::begin();

?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить категорию', ['post/add-category'], ['class' => 'btn btn-success']) ?>
    </p>



    <?= GridView::widget([
        'dataProvider' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'category',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{category-update} <br> {delete-category}",
                'buttons' =>
                    [
                        'category-update' => function ($url, $model, $key) {
                            return Html::a('Редактировать', $url);
                        },
                        'delete-category' => function ($url, $model, $key) {
                            return Html::a('Удалить', $url);
                        },
                    ]

            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>