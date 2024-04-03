<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->firstname . '  ' . $model->middlename;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'firstname',
            'middlename',
            'lastname',
            'birthday',
            'sex',
            'username',
            'password',
            'date_last_login',
            'role.role_user',
            'created_at',
            'updated_at',
            'status',
	        [
		        'attribute'=>'avatar',
		        'value'=> '@web/' . $model->avatar,
		        'format' => ['image',['width'=>'100','height'=>'100']],
	        ],
//            'access_token',
//            'auth_key',
        ],
    ]) ?>

</div>
