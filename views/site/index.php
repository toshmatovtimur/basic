 <?php

/** @var yii\web\View $this */

 use yii\bootstrap5\ActiveForm;
 use yii\helpers\Html;

 $this->title = 'Корпоративный сайт';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">

        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'fieldConfig' => [
                'template' => "{label}{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-4'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?php ActiveForm::end(); ?>
        <?= Html::img(Yii::getAlias('@web').'/tpu.jpg', ['alt' => 'image', 'width' => 600]);?>
        <h2>Главная страница с постами</h2>

    </div>
</div>
