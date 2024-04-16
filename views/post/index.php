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

    <p>
        <?= Html::a('Добавить Пост', ['post/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
	        //  'id',
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
            //  'alias',
            // 'date_publication',
            //'text_full:ntext',
            //'date_update_content',
            //'tags',
            //'fk_status',
            //'fk_user_create',
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
