<?php

use yii\helpers\Html;
	use yii\web\YiiAsset;
	use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Content $model */


$this->title = $model->header;
$this->params['breadcrumbs'][] = ['label' => 'Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="content-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'header',
            'alias',
            'date_create:datetime',
            'date_publication:date',
            'text_short',
            'text_full:html',
            'date_update_content:datetime',
            'tags',
            'user.username',
            'status.status_name',
//	        [
//		        'attribute'=>'photo',
//		        'value'=> function ($model)
//                {
//			        $html = '';
//			        foreach ($images as $img)
//                    {
//				        $html .= Html::img($img, ['width' => '100px', 'height' => '100px']);
//                    }
//			        return $html;
//		        },
//		        'format' => 'raw',
//	        ],
        ],
    ]) ?>

</div>
