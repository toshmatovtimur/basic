<?php

	use app\models\Content;
	use app\models\Contentandfoto;
	use app\models\Foto;
	use yii\helpers\Html;



	$model = Foto::find()
		->select(['path_to_foto'])
		->innerJoinWith('contentandfoto')
       // ->where(['contentandfoto.fk_content' => 1])
        ->all();

    if($model !== null) {
	    foreach ($model as $item) {
		    echo Html::img('@web/' . $item['path_to_foto'], ['alt' => 'фотка', 'width' => 300, 'class' => 'img-responsive']);
	    }
    }



    //debug($model);

//foreach(Images::model()->findAll() as $image)
//{
//    echo Html::img('/images/upload/'.$image->filename);
//}
//
//	Html::img(Yii::getAlias('@web') . '/tpu.jpg', ['alt' => 'image', 'width' => 600]); ?>
<!---->



