<?php

	use app\models\Content;
	use app\models\Contentandfoto;
	use app\models\Foto;
	use yii\helpers\Html;


	$datetime = new DateTime();

	var_dump($datetime);


//	$foto = Contentandfoto::find()
//		->select(['fk_foto'])
//		->where(['fk_content' => 30])
//		->all();
//
//	foreach ($foto as $item) {
//		echo $item->fk_foto;
//		echo '<br>';
//	}


//	$foto = Contentandfoto::find()
//		->select(['fk_foto'])
//		->where(['contentandfoto.fk_content' => 36])
//		->all();
//
//	foreach ($foto as $item) {
//		echo $item->fk_foto;
//		echo '<br>';
//	}







//	$path = "img/post-34";
//	if (is_dir($path)) {
//		rmdir($path);
//	}




//	$model = Foto::find()
//		->select(['path_to_foto'])
//		->innerJoinWith('contentandfoto')
//        ->where(['contentandfoto.fk_content' => $id])
//        ->all();
//
//    if ($model) {
//        foreach ($model as $item) {
//            echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 300, 'class' => 'img-responsive']);
//        }
//    } else {
//        echo 'Картинок нету';
//    }
//