<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

use yii\helpers\Html;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <br>
    <p><?php echo Html::img('@web/avatar/img.png', ['alt' => 'фотка', 'width' => 200, 'class' => 'img-responsive']); ?></p>
    <p><?= $model->firstname . ' ' . $model->middlename . ' ' . $model->lastname ?></p>
    <p><?php echo 'Возраст: ' . get_age($model->birthday) . ' лет' ?></p>
    <p><?= 'Роль: ' . $model->role->role_user ?></p>
	<?= Html::a('Редактировать', ['update'],  ['class'=>'btn btn-primary'])?>
	<?= Html::a('Удалить', ['/admin/update'], ['class'=>'btn btn-primary']) ?>





    <br><br><br><br><br><br><br><br><br><br><br><br><br><p>Задавайте вопросы на электронную почту:  <a href="mailto:kamentimur1991@gmail.com"> kamentimur1991@gmail.com</a></p>
</div>

