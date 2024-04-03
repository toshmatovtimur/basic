<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->firstname . ' ' . $model->middlename;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->firstname . ' ' . $model->middlename, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
	<?= Html::img('@web/' . $model->avatar, ['alt' => 'Изображение', 'width' => 150, 'class' => 'img-responsive']) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
