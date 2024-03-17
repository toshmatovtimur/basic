<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Content $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'header')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_create')->textInput() ?>

    <?= $form->field($model, 'date_publication')->textInput() ?>

    <?= $form->field($model, 'text_short')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text_full')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'date_update_content')->textInput() ?>

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fk_status')->textInput() ?>

    <?= $form->field($model, 'fk_user_create')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
