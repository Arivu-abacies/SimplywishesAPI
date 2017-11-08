<?php

namespace app\controllers;
use Yii;
use app\models\FollowRequest;
use app\models\Message;
use app\models\User;
use app\models\UserProfile;
use yii\helpers\Html;
use yii\helpers\Url;

class FollowController extends \yii\web\Controller
{
	public function actionFollowRequest()
    {		
		$model = new FollowRequest();
        $from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		
		if($from != '' && $to != '' ){
			$checkdata = FollowRequest::find()->where(["requested_by"=>$from,"requested_to"=>$to])->one();				
			if($checkdata)
			{				
				$checkdata->delete();
				echo "unfollow";
			}	
			else
			{
				$request = new FollowRequest();
				$request->requested_by = $from;
				$request->requested_to = $to ;			
				$request->save();	
				echo "follow";				
			}	
		}
    }

	public function actionCancelFollow()
    {	
		
	  $requestid = \Yii::$app->request->post()['requestid'];
	  if($requestid)
	  {
		$user_id = \Yii::$app->user->id;
		$checkdata = FollowRequest::find()->where(["requested_to"=>$requestid ,"requested_by" =>$user_id ])->one();	
		if($checkdata)
		{
			$checkdata->delete();
			return true;
		}			
		
	  }	
	}
	
}
