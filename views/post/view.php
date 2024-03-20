<?php

	use app\models\Foto;
	use yii\helpers\Html;
	use yii\web\YiiAsset;
	use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Content $model */


$this->title = $model->header;
$this->params['breadcrumbs'][] = ['label' => 'Contents', 'url' => ['index']];
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

<?php

	$request = Yii::$app->request;
	$id = $request->get('id');
    debug($id);

	$images = Foto::find()
->select(['path_to_foto'])
->innerJoinWith('contentandfoto')
 ->where(['contentandfoto.fk_content' => 1])
->all();


foreach ($images as $item) {
echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 300, 'class' => 'img-responsive']);
}