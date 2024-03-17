<?php

	use yii\helpers\Html;

	/** @var yii\web\View $this */
	/** @var app\models\PostForm $model */

	$this->title = 'Добавить контент';
	$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['upload']];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-upload">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model, 'error' => $error,
	]) ?>

</div>

