 <?php

/** @var yii\web\View $this */

 use yii\bootstrap5\ActiveForm;
 use yii\helpers\Html;

 $this->title = 'Корпоративный сайт';
?>

    <?php
        if (!empty($posts)) {
            foreach ($posts as $post) {
                echo '<br>';
	            echo Html::img('@web/' . $post['foto']['path_to_foto'], ['alt' => 'фотка', 'width' => 300, 'class' => 'img-responsive']);
            }
        }

    ?>





