<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ContentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'header') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'date_create') ?>

    <?= $form->field($model, 'date_publication') ?>

    <?php // echo $form->field($model, 'text_short') ?>

    <?php // echo $form->field($model, 'text_full') ?>

    <?php // echo $form->field($model, 'date_update_content') ?>

    <?php // echo $form->field($model, 'tags') ?>

    <?php // echo $form->field($model, 'fk_status') ?>

    <?php // echo $form->field($model, 'fk_user_create') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
