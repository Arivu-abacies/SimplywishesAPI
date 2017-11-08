<?php

namespace app\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;
use app\models\Subscription as SubscriptionModel;

class SubscriptionController extends ActiveController
{
    public $modelClass = 'app\models\Subscription';
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::className(),
		];
		return $behaviors;
	}
	public function actions()
	{
		$actions = parent::actions();
		// disable the "index" actions
		unset($actions['index']);
		return $actions;
	}

	/*
	 * Action for Add subscription for a user
	 * value is Dashboard Id
	 * Return JSON
	 */
	 
	public function actionAddSubscription($id){	
	 	
		$model =  SubscriptionModel::find()->where(['eq_customer_id'=>\Yii::$app->user->id,'dashboard_id'=>$id])->One();
		if(!$model)
		{
			$model = new SubscriptionModel();		
		}
		  $model->eq_customer_id  = \Yii::$app->user->id;
		  $model->dashboard_id = $id;
		  $model->created_at = date("Y-m-d H:i:s");
		  $model->status = 1;
		if($model->save())
		{
		   return ['Success' => "Subscription Is Added To This User Successfully"];			  
		}
		else { 
		   return ['Error' => $model->getErrors()]; 
		}	  		
	}	
	
	
	/*
	 * Action for Cancel subscription for a user
	 * value is Subscription Id
	 * Return JSON
	 */
	 
	public function actionCancelSubscription($subid){	
				
		$model =  SubscriptionModel::find()->where(['eq_customer_id'=>\Yii::$app->user->id,'subscription_id'=>$subid])->One();
		//$model->created_at = date("Y-m-d H:i:s");
		//$model->status = 2;
		if($model)
		{
		  $model->delete();	
		  return ['Success' => "Subscription Is Cancelled To This User Successfully"];			  
		}
		else { 
		  return ['Error' => $model->getErrors()]; 
		}
	  		
	}	
	
	
	
	/*
	 * Action for All Subscription Data of a Related user
	 *  
	 * Return JSON
	 */
	 
	public function actionUserSubscription(){					
		$model =  SubscriptionModel::find()->where(['eq_customer_id'=>\Yii::$app->user->id,'status'=>1])->All();	
		if($model)
		{
		   return $model;			  
		}
		else { 
		   return ['Error' => $model->getErrors()]; 
		}	  		
	}	
	
	/*
	 * Action for All Subscription Data of a Related Dashboard
	 * value is Dashboard Id 
	 * Return JSON
	 */
	 
	public function actionDashboardSubscription($id){
	$model_check = User::findOne(\Yii::$app->user->id);
	  if($model_check->role == "admin")
	  {			
		$model =  SubscriptionModel::find()->where(['dashboard_id'=>$id,'status'=>1])->All();	
		if($model)
		{
		   return $model;			  
		}
		else { 
		   return ['Error' => $model->getErrors()]; 
		}
		}  
		else { return ['Error' => "Access Denied"];	}  		
	}
	
	/*
	 * Action for subscription status of a dashboard for  a particular user 
	 * value is Dashboard Id 
	 * Return True Or False 
	 */
	 
	public function actionSubscriptionStatus($id){	
	
		$model =  SubscriptionModel::find()->where(['dashboard_id'=>$id,'eq_customer_id'=>\Yii::$app->user->id,'status'=>1])->One();	
		if($model)
		{
		   return True;			  
		}
		else { 
		   return False;		 
		}  		
	}
	
}