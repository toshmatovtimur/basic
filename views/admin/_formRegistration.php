<?php

	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;

	/** @var yii\web\View $this */
	/** @var app\models\User $model */
	/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'firstname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'middlename')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'lastname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'birthday')->input('date') ?>

	<?= $form->field($model, 'sex')->dropDownList(['Undefined' => 'Неизвестно', 'Male' => 'Мужской', 'Female' => 'Женский',]) ?>

	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'fk_role')->dropDownList(['1' => 'Пользователь', '2' => 'Администратор']) ?>

	<?= $form->field($model, 'created_at')->input('date') ?>

	<?= $form->field($model, 'status')->dropDownList(['10' => 'Активен', '0' => 'Удален']) ?>

	<div class="form-group">
		<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
