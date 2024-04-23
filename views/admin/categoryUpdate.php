<?php

	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/** @var yii\web\View $this */
	/** @var app\models\Category $model */

	$this->title = 'Обновление категории';
	$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['category']];
	$this->params['breadcrumbs'][] = $this->title;
?>
<div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
		<?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>