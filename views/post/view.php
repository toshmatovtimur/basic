<?php

	use app\models\Foto;
	use yii\helpers\Html;
	use yii\web\YiiAsset;
	use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Content $model */


$this->title = $model->header;
$this->params['breadcrumbs'][] = ['label' => 'Контент', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
//?>
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
        ],
    ]) ?>



</div>

<h4>Изображения</h4>

<?php

    if($images) {
        foreach ($images as $item) {
            echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 300, 'class' => 'img-responsive']);
        }
    } else {
        echo 'Изображений нет.';
    }
