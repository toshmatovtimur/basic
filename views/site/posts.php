<?php


use app\models\Content;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои посты';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

<?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          'header',
          'date_create',
          'text_short',
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
            'class' => 'yii\grid\ActionColumn',
            'template' => "{view} <br><br> {post/update}",
            'buttons' =>
            [
                'post/update' => function ($url, $model, $key) {
                    return Html::a('Редактировать', $url);
                },
                'view' => function ($url, $model, $key) {
                    return Html::a('Просмотр', $url);
                },
            ]

        ],
    ],
]);
