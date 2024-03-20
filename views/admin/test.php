<?php

	use app\models\Content;
	use app\models\Contentandfoto;
	use app\models\Foto;
	use yii\helpers\Html;


	$id = 1;

	$model = Foto::find()
		->select(['path_to_foto'])
		->innerJoinWith('contentandfoto')
        ->where(['contentandfoto.fk_content' => $id])
        ->all();


	    foreach ($model as $item) {
		    echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 300, 'class' => 'img-responsive']);
	    }







