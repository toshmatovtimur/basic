<?php

	/** @var yii\web\View $this */

	use app\models\Content;
	use app\models\View;
	use dosamigos\tinymce\TinyMce;
	use yii\bootstrap5\ActiveForm;
	use yii\bootstrap5\Html;
	use yii\captcha\Captcha;
	use yii\widgets\DetailView;

?>

<h1><?= $model->header; ?> </h1>
<br>
<?= $model->text_full ?>

<?php
    if ($images) {
        foreach ($images as $item) {
            echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'height' => 180, 'width' => 200, 'class' => 'img-responsive']) . '&nbsp&nbsp&nbsp&nbsp&nbsp';
        }
    } else {
        echo 'Картинок нету';
    }

    echo '<br><br><br>';

    // Запрос на получение количества просмотров данного поста
    $count = View::find()->select(['COUNT(fk_content) as counts'])->where(['fk_content' => $model->id])->one();
    echo 'Количество просмотров поста: ' . $count->counts;
    echo "<br>Дата публикации: " . $model->date_publication;

?>
<br><br><br>
<?php $commentForm->comment = "" ?>
<!-- Комментарии -->
<?php $form= ActiveForm::begin(); ?>
<?= $form->field($commentForm, 'comment')->widget(TinyMce::class, [
	'options' => ['rows' => 1, 'width' => 400],
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
    <div class="form-group">
    	<?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>



<?php foreach ($commentContent as $item): ?>
    <div class="comment">
        <p><?= $item->comment ?></p>
    </div>
<?php endforeach; ?>



<?php
    // Статистика просмотра поста
        $view = new View();
        $view->fk_content = $model->id;
        $view->fk_user = Yii::$app->user->id;
        $view->date_view = date("Y-m-d H:i:s");
        $view->save();
?>
