<?php

use app\models\Content;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\ContentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Contents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Content', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'header',
            'text_short',
            'date_create',
            //  'id',
            //  'alias',
            // 'date_publication',
            //'text_full:ntext',
            //'date_update_content',
            //'tags',
            //'fk_status',
            //'fk_user_create',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Content $model, $key, $index, $column)
                                {
                                     return Url::toRoute([$action, 'id' => $model->id]);
                                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>