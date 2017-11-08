<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;
use app\models\Customer;
use yii\filters\VerbFilter;

class GeneralController extends ActiveController
{
    public $modelClass = 'app\models\User';
	 	
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create-user' => ['POST'],
                ],				
            ],		
        ];
    }
	  
	public function actions()
	{
		$actions = parent::actions();
		// disable the "index" actions
		unset($actions['index'],$actions['view'],$actions['delete'],$actions['update'],$actions['create']);
		return $actions;
	}
	
	/*
	 * Action for creating the user
	 * POST 
	 * Return JSON
	 */

	public function actionCreateUser(){	
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;		
		$model = new User();			
		$model->username = $_POST['username'];
		$model->email	 = $_POST['email'];
		$model->role	 = 'user';
		$password = $model->generateUniqueRandomString("username");
		$model->setPassword($password);
		$model->generateAuthKey();
		if($model->save())
		{
			$customer = new Customer();
			$customer->eq_customer_id = $model->id;
			$customer->created_at = date("Y-m-d H:i:s");  
			if($customer->save()) 				
				return ['auth_key'=>$model->auth_key];
		} else { 
			return ['Error' => $model->getErrors()]; 
		}				
	}	
}