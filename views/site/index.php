 <?php

/** @var yii\web\View $this */

    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    use yii\widgets\Pjax;

	 $this->title = 'Корпоративный сайт';
	 Pjax::begin();
?>

 <?php //Вывод поста ?>
 <?php if (!empty($posts)): ?>
    <?php foreach ($posts as $post): ?>
        <?= Html::img('@web/' . $post->mainImage, ['alt' => 'фотка', 'width' => 600, 'height' => 410,'class' => 'img-responsive']);?>
         <h4><a href="<?= Url::to(['view', 'id' => $post->id]) ?>"><?= $post->header ?></a></h4>
        <p><?= $post->text_short ?></p><br>
    <?php endforeach; ?>
 <?php endif; ?>

 <?php if (empty($posts)): ?>
     <h4>По вашему запросу не найдено записей</h4>
 <?php endif; ?>

 <?php
     echo LinkPager::widget([
             'pagination' => $pages,
         ]);

     Pjax::end();
?>