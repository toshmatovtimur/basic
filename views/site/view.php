<?php

	/** @var yii\web\View $this */

	use app\models\Content;
	use app\models\View;
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
<!-- Комментарии -->
<?php $form= ActiveForm::begin(); ?>
<?= $form->field($commentForm, 'comment')->textarea(['rows' => 4]) ?>
    <div class="form-group">
    	<?= Html::submitButton('Добавить комментарий', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>



<?php //foreach ($post->comments as $comment): ?>
<!--    <div class="comment">-->
<!--        <p>--><?php //= $comment->text ?><!--</p>-->
<!--    </div>-->
<?php //endforeach; ?>



<?php
    // Статистика просмотра поста
        $view = new View();
        $view->fk_content = $model->id;
        $view->fk_user = Yii::$app->user->id;
        $view->date_view = date("Y-m-d H:i:s");
        $view->save();
?>
