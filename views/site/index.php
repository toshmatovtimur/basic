 <?php

/** @var yii\web\View $this */

 use yii\bootstrap5\ActiveForm;
 use yii\helpers\Html;

 $this->title = 'Корпоративный сайт';
?>

    <div style="right: auto">
	    <?php if (!empty($posts)): ?>
		    <?php foreach ($posts as $post): ?>
                <div class="content-grid">
                    <div class="content-grid-info">
<<<<<<< HEAD
=======
					    <?= Html::img('@web/' . $post->mainImage, ['alt' => 'фотка', 'width' => 600, 'height' => 400, 'class' => 'img-responsive']);?>
>>>>>>> f32bca0 (Корректировка)
                        <div class="post-info">
                            <br><h4><a href="<?= yii\helpers\Url::to(['view', 'id' => $post->id]) ?>"><?= $post->header ?></a></h4>
                        </div>
	                    <?= Html::img('@web/' . $post->mainImage, ['alt' => 'фотка', 'width' => 600, 'class' => 'img-responsive']);?>
                    </div>
                </div>
                <br><br>
		    <?php endforeach; ?>
	    <?php endif; ?>
    </div>




