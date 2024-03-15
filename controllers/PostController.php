<?php

namespace app\controllers;

use app\models\UserIdentity;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class PostController extends Controller
{

    /**
     * Правила для Контроллера
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
//                        'matchCallback' => function($rule, $action)
//                        {
//                            return UserIdentity::isAdmin();
//                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * index for PostController
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    
}