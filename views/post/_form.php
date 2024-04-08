<?php

use app\models\Foto;
use dosamigos\tinymce\TinyMce;
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

    <?= $form->field($model, 'tags')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fk_status')->dropDownList(['1' => 'Создан', '2' => 'Опубликован', '3' => 'Архивирован',]) ?>

    <?= $form->field($model, 'imageContent[]')->fileInput(['multiple' => true, 'accept' => '@web/img/*']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>



    <?php ActiveForm::end(); ?>

    <h4>Ранее загруженные изображения:</h4>
    <?php

    $images = Foto::find()
        ->select(['path_to_foto'])
        ->innerJoinWith('contentandfoto')
        ->where(['contentandfoto.fk_content' => $model->id])
        ->all();


    if ($images) {
        foreach ($images as $item) {
            echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 250, 'class' => 'img-responsive']);
        }
    } else {
        echo 'Картинок нету';
    }
    ?>

</div>
