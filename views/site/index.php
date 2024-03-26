 <?php

/** @var yii\web\View $this */

 use yii\bootstrap5\ActiveForm;
 use yii\helpers\Html;

 $this->title = 'Корпоративный сайт';
 $image = null;
 $idContent = 0;
?>

    <div style="right: auto">
	    <?php if (!empty($posts)): ?>
		    <?php foreach ($posts as $post): ?>
                <div class="content-grid">
                    <div class="content-grid-info">
                        <?php
                            if($idContent !== $post['content']['id']) {
	                            $idContent = $post['content']['id'];
	                            $image = null;
                            }

                            if ($image === null) {
	                            $image = '@web/' . $post['foto']['path_to_foto'];
                            }


                            ?>
					    <?= Html::img($image, ['alt' => 'фотка', 'width' => 600, 'class' => 'img-responsive']);?>
                        <div class="post-info">
                            <h4><a href="<?= yii\helpers\Url::to(['view', 'id' => $post['content']['id']]) ?>"><?= $post['content']['header'] ?></a></h4>
                            <p><?= $post['content']['text_short'] ?></p>
                        </div>
                    </div>
                </div>
		    <?php endforeach; ?>
	    <?php endif; ?>
    </div>




