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

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::beginTag('div', ['class' => 'btn-group']) ?>
        <?= Html::a('Добавить пост', ['post/upload'], ['class' => 'btn btn-success']) ?>
    <?= Html::endTag('div') ?>

    <?php Pjax::begin(); ?>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'header',
            'text_short',
            'date_create',
	        [
		        'attribute' => 'user',
		        'value' => 'user.username'
	        ],
	        [ // Картинка
		        'attribute' => 'mainImage',
		        'format' => 'html',
		        'label' => 'Обложка поста',
		        'value' => function ($data)
		        {
			        return Html::img('@web/' . $data['mainImage'], ['width' => '150px',
				        'height' => '120px']);
		        },

	        ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Content $model, $key, $index, $column)
                                {
                                     return Url::toRoute([$action, 'id' => $model->id]);
                                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
