 <?php

/** @var yii\web\View $this */

 use yii\bootstrap5\ActiveForm;
 use yii\bootstrap5\LinkPager;
 use yii\helpers\Html;


 $this->title = 'Корпоративный сайт';
?>

    <div style="right: auto">
	    <?php if (!empty($posts)): ?>
		    <?php foreach ($posts as $post): ?>
					    <?= Html::img('@web/' . $post->mainImage, ['alt' => 'фотка', 'width' => 600, 'height' => 400, 'class' => 'img-responsive']);?>
                            <h4><a href="<?= yii\helpers\Url::to(['view', 'id' => $post->id]) ?>"><?= $post->header ?></a></h4>
                            <p><?= $post->text_short ?></p>
                </div>
		    <?php endforeach; ?>
	    <?php endif; ?>


