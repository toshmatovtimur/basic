<?php

	use app\models\Category;
	use dosamigos\tinymce\TinyMce;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
    use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PostForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form upload">

    <?= $error ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'header')->textInput() ?>

    <?= $form->field($model, 'alias')->textInput() ?>

    <?= $form->field($model, 'text_short')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text_full')->widget(TinyMce::class, [
        'options' => ['rows' => 6],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        ]
    ]);?>

    <?= $form->field($model, 'tags')->textInput() ?>

    <?php
	    $items = ArrayHelper::map(Category::find()->all(), 'id', 'category'); // Получите список объектов и сопоставьте их с полями 'id' и 'name'
    ?>

    <?= $form->field($model, 'category_fk')->dropDownList($items) ?>

    <?= $form->field($model, 'image[]')->fileInput(['multiple' => true, 'accept' => '@web/img/*']) ?>

    <div class="form-group">
        <?= Html::submitButton('Загрузить пост', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
