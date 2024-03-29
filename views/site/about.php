<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <br>
    <p><?php echo Html::img('@web/avatar/img.png', ['alt' => 'фотка', 'width' => 200, 'class' => 'img-responsive']); ?></p>
    <p>Лазарев Лис Лисович</p>
    <p>29 лет</p>
    <p>Роль: Пользователь</p>
    <button>Редактировать профиль</button><br><br>
    <button>Удалить профиль профиль</button>

    <br><br><br><br><br><br><br><br><br><br><br><br><br><p>Задавайте вопросы на электронную почту:  <a href="mailto:kamentimur1991@gmail.com"> kamentimur1991@gmail.com</a></p>
</div>
