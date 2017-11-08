<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;
use app\models\Customer;


class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';
	
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
		unset($actions['index'],$actions['view'],$actions['delete'],$actions['update'],$actions['create']);
		return $actions;
	}
	
	/*
	 * Test action for user endpoint
	 */
	public function actionTest(){
		return ['hi'];
	}

	/*
	 * Action for View Authorizated user Details
	 * GET 
	 * Return JSON
	 */
	 
	public function actionViewUser(){		
		$model = User::findOne(\Yii::$app->user->id);	 
		if($model)
	    {
			$datas = [
			'username'=>$model->username,
			'email'	 =>$model->email,
			'role'	 =>$model->role,
			];  
		
		   return $datas;
		}  
		else { return ['Error' => $model->getErrors()];	}   
	} 
	
	/*
	 * Action for Authorizated user Can Deleted
	 * GET 
	 * Return JSON
	 */
	 
	public function actionDeleteUser(){				
		$model = User::findOne(\Yii::$app->user->id);							
		  if($model->delete())
		  {
			$customer = Customer::find()->where(['eq_customer_id'=>\Yii::$app->user->id])->one();
			if($customer->delete())
			  return ['Success'=>"User Has Been Removed Successfully"];	
		  }		  
		 else { return ['Error' => $model->getErrors()]; }
	}
	
	/*
	 * Action for Authorizated user Can Update his Details
	 * POST 
	 * Return JSON
	 */
	 
	public function actionUpdateUser(){				
		$model = User::findOne(\Yii::$app->user->id);
		$customer = Customer::find()->where(['eq_customer_id'=>\Yii::$app->user->id])->one();		
		$model->email = $_POST['email'];
			if($model->save())
			{
			  $customer->updated_at = date("Y-m-d H:i:s");
			  if($customer->save())
				return ['Success'=>"User Details Has Been Updated Successfully"];			  
			} 	
			else { return ['Error' => $model->getErrors()]; }
	}	

	
	/*
	 * Action for View User Details By Admin User
 	 * GET 
	 * Return JSON
	 */
	 
	public function actionAdminViewUser($id){	
		$model_check = User::findOne(\Yii::$app->user->id);
		if($model_check->role == "admin")
		{
			$model = User::findOne($id);	 
			if($model)
			{
				$datas = [
				'username'=>$model->username,
				'email'	 =>$model->email,
				'role'	 =>$model->role,
				];  
			
			   return $datas;
			}  
			else { return ['Error' => $model->getErrors()];	} 
		}  
		else { return ['Error' => "Access Denied"];	} 			
	} 
	
	/*
	 * Action for User Can Deleted By Admin User
	 * GET 
	 * Return JSON
	 */
	 
	public function actionAdminDeleteUser($id){
	  $model_check = User::findOne(\Yii::$app->user->id);
	  if($model_check->role == "admin")
	  {		
		$model = User::findOne($id);							
		  if($model->delete())
		  {
			$customer = Customer::find()->where(['eq_customer_id'=>$id])->one();
			if($customer->delete())
			  return ['Success' =>"User Has Been Removed Successfully"];	
		  }		  
		 else { return ['Error' => $model->getErrors()]; }
	   }  
		else { return ['Error' => "Access Denied"];	}  
	}
	
	/*
	 * Action for User Can Update his Details By Admin User
	 * POST 
	 * Return JSON
	 */
	 
	public function actionAdminUpdateUser($id){	
	  $model_check = User::findOne(\Yii::$app->user->id);
	  if($model_check->role == "admin")
	  {		
		$model = User::findOne($id);
		$customer = Customer::find()->where(['eq_customer_id'=>$id])->one();		
		$model->email = $_POST['email'];
			if($model->save())
			{
			  $customer->updated_at = date("Y-m-d H:i:s");
			  if($customer->save())
				return ['Success' => "User Details Has Been Updated Successfully"];			  
			} 	
			else { return ['Error' => $model->getErrors()]; }
	  }  
		else { return ['Error' => "Access Denied"];	} 		
	}	

	
}