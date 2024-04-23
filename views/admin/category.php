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
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'category',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => "{post/update} <br> {delete-category}",
                'buttons' =>
                    [
                        'post/update' => function ($url, $model, $key) {
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