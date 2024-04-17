<?php
	/** @var yii\web\View $this */
	use app\models\Content;
	use app\models\View;
	use dosamigos\tinymce\TinyMce;
	use yii\bootstrap5\ActiveForm;
	use yii\bootstrap5\Html;
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
<?php $commentForm->comment = null ?>
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
    </div><br>
<?php ActiveForm::end(); ?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

<style>
    .comments ul ul {
        margin-left: 60px;
    }
    .comments .comment img {
        margin-right: 20px;
    }
    .comments .comment {
        padding: 6px;
    }
    .comments .comment:hover {
        background: #eee;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-body comments">
                    <ul class="media-list">
                            <div class="comment">
                                <a href="#" class="pull-left">
                                    <img src="https://bootstraptema.ru/snippets/element/2016/comments/com-3.jpg" alt="" class="img-circle">
                                </a>
                                <div class="media-body">
                                    <strong class="text-success">Пользователь 3</strong>
                                    <span class="text-muted">
                                        <small class="text-muted">2016-02-09</small>
                                    </span>
                                    <span class="text-muted pull-right">
                                        <small class="btn btn-success"><i>&#9998</i></small>
                                    </span>
                                    <p>
                                        Здесь текст комментария
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Раздел комментарии -->
<?php if (!empty($commentContent)): ?>
    <?php foreach ($commentContent as $item): ?>
        <div class="comment">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-body comments">
                                <ul class="media-list">
                                    <div class="comment">
                                        <a href="#" class="pull-left">
                                            <img src="https://bootstraptema.ru/snippets/element/2016/comments/com-3.jpg" alt="" class="img-circle">
                                        </a>
                                        <div class="media-body">
                                            <strong class="text-success">Пользователь 3</strong>
                                            <span class="text-muted">
                                        <small class="text-muted">2016-02-09</small>
                                    </span>
                                            <span class="text-muted pull-right">
                                        <small class="btn btn-success"><i>&#9998</i></small>
                                    </span>
                                            <p>
                                                Здесь текст комментария
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<?php
    // Статистика просмотра поста
        $view = new View();
        $view->fk_content = $model->id;
        $view->fk_user = Yii::$app->user->id;
        $view->date_view = date("Y-m-d H:i:s");
        $view->save();
?>
