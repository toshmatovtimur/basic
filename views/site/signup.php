<?php

	/** @var yii\web\View $this */
	/** @var yii\bootstrap5\ActiveForm $form */
	/** @var app\models\User $model */

	use yii\bootstrap5\ActiveForm;
	use yii\bootstrap5\Html;
	use yii\captcha\Captcha;

	$this->title = 'Регистрация';
	$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-registration">
    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <?php echo debug($error)  ?>
    </div>
    <p>Пожалуйста заполните поля ниже для регистрации:</p>

    <div class="row">
        <div class="col-lg-4">

			<?php $form = ActiveForm::begin([
				'id' => 'signup-form',
				'fieldConfig' => [
					'template' => "{label}{input}\n{error}",
					'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-4'],
					'inputOptions' => ['class' => 'col-lg-3 form-control'],
					'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
				],
			]); ?>

			<?= $form->field($model, 'firstname')->textInput(['placeholder' => 'Фамилия']) ?>

			<?= $form->field($model, 'middlename')->textInput(['placeholder' => 'Имя']) ?>

			<?= $form->field($model, 'lastname')->textInput(['placeholder' => 'Отчество']) ?>

			<?= $form->field($model, 'birthday')->textInput(['placeholder' => 'Дата рождения'])->input('date') ?>

			<?= $form->field($model, 'sex')->dropDownList(['Male' => 'Мужской', 'Female' => 'Женский']) ?>

			<?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин']) ?>

			<?= $form->field($model, 'password')->passwordInput() ?>

			<?= $form->field($model, 'confirm')->passwordInput() ?>

			<?= $form->field($model, 'verifyCode')->widget(Captcha::class,['template' => '{input}{image}']) ?>

            <div class="form-group">
                <div>
					<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>