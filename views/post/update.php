<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Content $model */

$this->title = 'Редактировать пост № ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Контент', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->header, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [

            'model' => $model,
    ]) ?>

</div>
