<?php

	use app\models\Content;
	use app\models\Contentandfoto;


//	$test = Content::find()
//			->joinWith('contentandfoto')
//			->where(['order.status' => Order::STATUS_ACTIVE])
//			->all();

	$model = Contentandfoto::find()
		->innerJoinWith('content', 'content.id = contentandfoto.fk_content')
		->innerJoinWith('foto', 'foto.id = contentandfoto.fk_foto')
		->all();
	debug($model);






