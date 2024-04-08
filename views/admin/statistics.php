<h1>Статистика</h1>
<br><br>


<?php

	use yii\grid\GridView;



    echo '<h3>Топ 10 проматриваемых страниц</h3>';
    // Топ 10 проматриваемых страниц
	echo GridView::widget([
		'dataProvider' => $topProvider,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			'content.header',
			'counts',
		],
	]);


    













