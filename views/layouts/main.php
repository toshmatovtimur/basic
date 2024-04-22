<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\Category;
use app\models\UserIdentity;
use app\widgets\Alert;
use matejch\yii2sidebar\Sidebar;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/tpu-icon.jpg')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php

    NavBar::begin([
//        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
             !Yii::$app->user->isGuest
            ? ['label' => 'Личный кабинет', 'url' => ['/site/about']] : '<p></p>',
//            ['label' => 'Contact', 'url' => ['/site/contact']],

            UserIdentity::isAdmin()
                ?  ['label' => 'Admin', 'url' => ['/admin/adm']]
                : '<p></p>',

           ['label' => 'Test', 'url' => ['/admin/test']],

            Yii::$app->user->isGuest
                ? ['label' => 'Вход', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Выход (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>',
            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
        Html::beginForm(['site/search']),
	    Html::textInput('search',"",['placeholder' => 'Поиск'] ),
	    Html::endForm(),
        ]
    ]);
    NavBar::end();
    ?>
</header>

<div class="sidebar">
	<?php Sidebar::begin([

		'collapseText' => 'Скрыть боковую панель', // Optional text in button, defaults to Collapse
		'top' => '75px', //Optional Fixed top, where sidebar begins, defaults to 0px
		'left' => '0px', //Optional Fixed left, where sidebar begins on letf side, defaults to 0px
		'widthOpen' => '256px', //Optional size when sidebar is opened
		'widthCollapsed' => '70px', //Optional size when sidebar is colapsed
		'topMobile' => '0px', //Optional
		'leftMobile' => '0px', //Optional
		'position' => 'fixed', //Optional
		'positionMobile' => 'fixed' //Optional
	]) ?>
    <h4 class="">Категории</h4>
    <br>
    <?php
	    $categories = Category::find()->orderBy(['category' => SORT_DESC]) ->all();
    ?>

	<?php foreach ($categories as $value): ?>
        <div>
			<?php echo Html::a("<i class='fas fa-eye'></i> <span data-sidebar-hide='1'>$value->category</span>", Url::to(['site/index', 'id' => $value->id]), ['class' => "btn btn-success"]) ?>
        </div>
        <br>
	<?php endforeach; ?>
    <br>

	<?php Sidebar::end() ?>
</div>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">Томский политехнический университет <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
