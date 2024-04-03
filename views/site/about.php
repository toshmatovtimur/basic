<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */

    use yii\helpers\Html;
	use yii\helpers\Url;

	$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <br>
    <p><?= Html::img('@web/' . $model->avatar, ['alt' => 'фотка', 'width' => 200, 'class' => 'img-responsive']); ?></p>
    <p><?= $model->firstname . ' ' . $model->middlename . ' ' . $model->lastname ?></p>
    <?php if($model->birthday !== null)
	    echo 'Возраст: ' . get_age($model->birthday) . ' лет';
    ?>
    <p><?= 'Роль: ' . $model->role->role_user ?></p>

    <br><br>

    <style>
        .box{
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 5px #555;
        }
        .box img{
            width: 100%;
            height: auto;
            transition: all 0.5s ease 0s;
        }
        .box:hover img{
            opacity: 0.3;
        }
        .box .boxContent{
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            padding: 30px;
            background: rgba(0, 0, 0, 0.75);
            border: 4px solid rgba(255, 255, 255, 0.10);
            -webkit-transform: rotate(90deg);
            transform: rotate(90deg);
            -webkit-transform-origin: 0 0;
            transform-origin: 0 0;
            z-index: 1;
            transition: all 0.5s ease 0s;
        }
        .box:hover .boxContent{
            -webkit-transform: rotate(0);
            transform: rotate(0);
            -webkit-transform-origin: 100% 100%;
            transform-origin: 100% 100%;
        }
        .box .title{
            display: inline-block;
            font-size: 30px;
            color: #fff;
            line-height: 45px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin: 0;
        }
        .box .post{
            display: block;
            font-size: 15px;
            color: #fff;
            text-transform: capitalize;
            padding: 8px 0 15px;
        }
        @media only screen and (max-width: 990px){
            .box{ margin-bottom: 20px; }
        }
    </style>

    <div class="container">
        <div class="row">

            <div class="col-md-4 col-sm-6">
                <div class="box">
                    <a href="update">
	                    <?= Html::img('@web/task.png', ['alt' => '', 'width' => 100, 'height' => 200]) ?>
                        <div class="boxContent">
                            <h3 class="title">Редактировать профиль</h3>
                        </div>
                    </a>

                </div>
            </div>



            <div class="col-md-4 col-sm-6">
                <div class="box">
                    <a href="posts">
	                    <?= Html::img('@web/content1.png', ['alt' => '', 'width' => 100, 'height' => 200]) ?>
                        <div class="boxContent">
                            <h3 class="title">Мои посты</h3>
                        </div>
                    </a>

                </div>
            </div>


        </div><!-- ./row -->
    </div><!-- ./container -->







    <br><br><br><br><br><br><br><br><br><br><br><br><br><p>Задавайте вопросы на электронную почту:  <a href="mailto:kamentimur1991@gmail.com"> kamentimur1991@gmail.com</a></p>
</div>

