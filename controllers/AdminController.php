<?php

	namespace app\controllers;

	use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\Controller;

	class AdminController extends Controller
	{

        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => [ 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ];
        }

        


		public function actionIndex()
		{
			return $this->render('index');
		}


	}