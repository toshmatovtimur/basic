<?php

	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/** @var yii\web\View $this */
	/** @var app\models\User $model */
	/** @var yii\widgets\ActiveForm $form */

	$this->title = 'Редактирование профиля';
	$this->params['breadcrumbs'][] = $this->title;

?>
<br><br>
    <p>
	    <?= Html::a('Удалить профиль', ['delete'], ['class' => 'btn btn-success']) ?>
    </p>


	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'middlename')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'birthday')->input('date') ?>

	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'avatarImage')->fileInput() ?>

	<div class="form-group">
		<?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>


