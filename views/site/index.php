 <?php

/** @var yii\web\View $this */

    use yii\bootstrap5\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
    
	 $this->title = 'Корпоративный сайт';
?>

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
 // Создание ссылок для пагинации
 if ($pages->getPageCount() >= 1) {
 echo '<ul class="pagination">';
     // Кнопка "первая страница"
     echo '<li>' . Html::a('<<', ['index', 'page' => 1]) . '</li>';
     // Предыдущие страницы
     for ($i = 1; $i <= $pages->getPageCount(); $i++) {
     echo '<li>' . Html::a($i, ['index', 'page' => $i]) . '</li>';
     }
     // Кнопка "последняя страница"
     echo '<li>' . Html::a('>>', ['index', 'page' => $pages->getPageCount()]) . '</li>';
     echo '</ul>';
 }
 ?>
