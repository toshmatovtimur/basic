<?php

	/** @var yii\web\View $this */

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
            echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 200, 'class' => 'img-responsive']);
            echo '<br>';
            echo '<br>';
        }
    } else {
        echo 'Картинок нету';
    }

?>

<?php
    // Статистика просмотра поста
        $view = new View();
        $view->fk_content = $model->id;
        $view->fk_user = Yii::$app->user->id;
        $view->date_view = date("Y-m-d H:i:s");
        $view->save();



