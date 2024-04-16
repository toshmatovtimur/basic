 <?php

/** @var yii\web\View $this */

 use yii\bootstrap5\ActiveForm;
	 use yii\bootstrap5\LinkPager;
	 use yii\helpers\Html;


	 $this->title = 'Корпоративный сайт';
?>

    <div style="right: auto">
	    <?php if (!empty($models)): ?>
		    <?php foreach ($models as $post): ?>
                <div class="content-grid">
                    <div class="content-grid-info">
					    <?= Html::img('@web/' . $post->mainImage, ['alt' => 'фотка', 'width' => 600, 'height' => 400, 'class' => 'img-responsive']);?>
                        <div class="post-info">
<<<<<<< HEAD
                            <br><h4><a href="<?= yii\helpers\Url::to(['view', 'id' => $post['id']]) ?>"><?= $post['header'] ?></a></h4>
                        </div>
	                    <?= Html::img('@web/' . $post['mainImage'], ['alt' => 'фотка', 'width' => 600, 'class' => 'img-responsive']);?>
=======
                            <h4><a href="<?= yii\helpers\Url::to(['view', 'id' => $post->id]) ?>"><?= $post->header ?></a></h4>
                            <p><?= $post->text_short ?></p>
                        </div>
>>>>>>> 877ffe0002f775a9a9bfca3ce7f5279b2aeec91f
                    </div>
                </div>
                <br>
		    <?php endforeach; ?>
            <?php echo LinkPager::widget([
            'pagination' => $pages,
            ]); ?>
	    <?php endif; ?>
    </div>
<<<<<<< HEAD
=======


>>>>>>> 877ffe0002f775a9a9bfca3ce7f5279b2aeec91f
