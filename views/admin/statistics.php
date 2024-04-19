<h1>Статистика</h1>
<br><br>


<?php

	use yii\grid\GridView;


    echo '<h3>Топ 10 просматриваемых страниц</h3>';
	echo GridView::widget([
		'dataProvider' => $topProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'content.header',
			'counts',
		],
	]);

    echo '<br><br>';

	echo '<h3>10 последних созданных страниц</h3>';
	echo GridView::widget([
		'dataProvider' => $lastCreateProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'header',
			'date_create',
		],
	]);

	echo '<br><br>';

	echo '<h3>Топ-10 страниц, текст которых обновлялся более 1-месяца назад</h3>';
	echo GridView::widget([
		'dataProvider' => $mouthUpdateProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'content.header',
			'content.date_update_content',
			'counts',
		],
	]);

	echo '<br><br>';

	echo '<h3>Топ-10 активных пользователей (больше всего комментариев за последнюю неделю)</h3>';
	echo GridView::widget([
		'dataProvider' => $topActiveUsers,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
            'user.firstname',
			'counts',
		],
	]);