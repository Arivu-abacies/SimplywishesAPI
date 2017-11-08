<?php

namespace app\controllers;
use Yii;
use app\models\FriendRequest;
use app\models\Message;
use app\models\User;
use app\models\UserProfile;
use yii\helpers\Html;
use yii\helpers\Url;

class FriendController extends \yii\web\Controller
{
	public function actionFriendRequest()
    {		
		$model = new FriendRequest();
        $from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		
		if($from != '' && $to != '' ){
			$checkdata = FriendRequest::find()->where(["requested_by"=>$from,"requested_to"=>$to])->one();	
			
			if($checkdata)
			{
				$checkdata->updated_at = date('Y-m-d H:i:s');
				$checkdata->save();
				//$friend_request_id = $checkdata->f_id;
			}	
			else
			{
				$request = new FriendRequest();
				$request->requested_by = $from;
				$request->requested_to = $to ;			
				$request->save();
				//$friend_request_id = $request->f_id;
			}	
			
			 $model->sendEmail($to);
			
			
/* 			$profile = UserProfile::find()->where(['user_id'=>$to])->one();			
			$msg = $profile->firstname.' '.$profile->lastname.' Send Friend Request to you <a style="color:#337ab7 !important"  href="'.Url::to(['friend/request-accepted','id'=>$friend_request_id]).'"><u>Accept</u></a>';
		
			$message = new Message();
			$message->sender_id = $from;
			$message->recipient_id = $to;
			$message->parent_id = 0;
			$message->text = $msg;
			$message->save();  
 */
		}
    }
	
	public function actionRequestAccepted()
    {	
		
	  $requestid = \Yii::$app->request->post()['requestid'];
	  if($requestid)
	  {
		$user_id = \Yii::$app->user->id;
		$checkdata = FriendRequest::find()->where(["requested_to"=>$user_id,"status"=>0,"f_id"=>$requestid])->one();		
		if($checkdata)
		{
			$checkdata->status = 1;
			$checkdata->save();
			return true;
		}	
	  }	
	}
	
	public function actionCancelFriend()
    {	
		
	  $requestid = \Yii::$app->request->post()['requestid'];
	  if($requestid)
	  {
		$user_id = \Yii::$app->user->id;
		$checkdata = FriendRequest::find()->where(["status"=>1,"f_id"=>$requestid])->one();	
		if(($checkdata->requested_by == $user_id) || ($checkdata->requested_to == $user_id))
		{
			$checkdata->delete();
			return true;
		}			
		
	  }	
	}
	
/* 	public function actionRequestAccepted()
    {		
		$id = \Yii::$app->request->get()['id'];
		$user_id = \Yii::$app->user->id;
		$checkdata = FriendRequest::find()->where(["requested_to"=>$user_id,"status"=>0,"f_id"=>$id])->one();		
		if($checkdata)
		{
			$checkdata->status = 1;
			$checkdata->save();
		}	
		
		return $this->redirect(['account/friend-requested']);
	} */
	
	public function actionMyFriend(){
		$user = User::findOne(\Yii::$app->user->id);		
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$myfriend = FriendRequest::find()->where(["requested_by"=>\Yii::$app->user->id])->orWhere(["requested_to"=>\Yii::$app->user->id])->andWhere(["status"=>1])->all();	
		
		return $this->render('my_friend', 
						[
						 'user' => $user,	
						 'profile' => $profile,
						 'myfriend' => $myfriend,
						]);		
	}
	
}
