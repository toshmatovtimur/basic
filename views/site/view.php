<?php

	/** @var yii\web\View $this */

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

