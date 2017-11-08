<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\UserProfile;
use yii\web\UploadedFile;
use app\models\search\SearchWish;
use app\models\Message;
use app\models\Wish;
use app\models\FriendRequest;
use app\models\FollowRequest;

class AccountController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['edit-account','my-account','inbox'],
                'rules' => [
                    [
                        'actions' => ['edit-account','my-account','inbox'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
	
	public function actionProfile($id){
		if($id==\Yii::$app->user->id)
			return $this->redirect('my-account');
		$user = User::findOne($id);
		$profile = UserProfile::find()->where(['user_id'=>$id])->one();
		
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchUserWishes(Yii::$app->request->queryParams,$id,'active');
		
		return $this->render('other_profile', [
            'user' => $user,
			'profile' => $profile,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
	public function actionMyAccount(){
		
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchUserWishes(Yii::$app->request->queryParams,\Yii::$app->user->id,'active');
		
		return $this->render('profile', [
            'user' => $user,
			'profile' => $profile,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
	
	public function actionMyFullfilled($id=null){
		if(!$id)
			$id = \Yii::$app->user->id;
		$user = User::findOne($id);
		$profile = UserProfile::find()->where(['user_id'=>$id])->one();
		
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchUserWishes(Yii::$app->request->queryParams,$id,'fullfilled');
		if($id == \Yii::$app->user->id)
			return $this->render('my_fullfilled', [
				'user' => $user,
				'profile' => $profile,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);	
		else
			return $this->render('other_fullfilled', [
				'user' => $user,
				'profile' => $profile,
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
			]);			
	}
	public function actionMySaved($id=null){
		if(!$id)
			$id = \Yii::$app->user->id;		
		$user = User::findOne($id);
		$profile = UserProfile::find()->where(['user_id'=>$id])->one();
		
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchSavedWishes(Yii::$app->request->queryParams,\Yii::$app->user->id);
		
		return $this->render('my_saved', [
            'user' => $user,
			'profile' => $profile,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);			
	}
	
	public function actionMyProgress(){
		
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		
        $searchModel = new SearchWish();
        $dataProvider = $searchModel->searchProcessWishes(Yii::$app->request->queryParams,\Yii::$app->user->id,'active');
		
		return $this->render('my_progress', [
            'user' => $user,
			'profile' => $profile,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);		
	}
	
	
	public function actionEditAccount()
	{	
		$id = \Yii::$app->user->id;
		$user = User::findOne(\Yii::$app->user->id);
		
		
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
		$states = \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(['country_id'=>$profile->country])->all(),'id','name');	
		$cities = \yii\helpers\ArrayHelper::map(\app\models\City::find()->where(['state_id'=>$profile->state])->all(),'id','name');	
		
		$user->scenario = 'updatecheck';
		$current_image = $profile->profile_image;
	
		
		
		if (($user->load(Yii::$app->request->post())  && $user->validate()) && $profile->load(Yii::$app->request->post())){
			
			
			if($user->password)
				$user->setPassword($user->password);
			
			if($user->save()){
				
				$profile->user_id = $user->id;								
				$profile->profile_image = UploadedFile::getInstance($profile, 'profile_image');
			
				if(!empty($profile->profile_image)) {
					if(!$profile->uploadImage())
						return;
				}else{
					
					if(!empty($profile->dulpicate_image) && ($profile->dulpicate_image != $current_image ))
					{
						$profile->profile_image = $profile->dulpicate_image;
					} else {
						$profile->profile_image = $current_image;
					}
					
				}
				$profile->save();
				$profile->sendProfileEmail(\Yii::$app->user->id);		
				/* \Yii::$app->getSession()->setFlash('success', 'Account details have been changed successfully');
				return $this->redirect('my-account'); */
				
				\Yii::$app->getSession()->setFlash('success', 'Account details have been changed successfully');
				return $this->redirect(['site/index-home']);
				
			}
		}		
		else 
		{ 	
		
		
		return $this->render('my_account', [
				'user' => $user,
				'profile' => $profile,
				'countries' => $countries,
				'states' => $states,
				'cities' => $cities
			]);
		}
	}
	
	public function actionSendMessage(){
		
		$from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		$msg = \Yii::$app->request->post()['msg'];
		
		if($from != '' && $to != '' && $msg != ''){
			$message = new Message();
			$message->sender_id = $from;
			$message->recipient_id = $to;
			$message->parent_id = 0;
			$message->text = $msg;
			$message->created_at = date("Y-m-d H:i:s");
			if($message->save()){
				Yii::$app->session->setFlash('messageSent');
			}
				return $this->redirect(['profile','id'=>$to]);
		}
	}
	
	
	public function actionSendMessageWishes(){
		
		$from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		$msg = \Yii::$app->request->post()['msg'];
		
		if($from != '' && $to != '' && $msg != ''){
			$message = new Message();
			$message->sender_id = $from;
			$message->recipient_id = $to;
			$message->parent_id = 0;
			$message->text = $msg;
			$message->read_text = $to;
			$message->created_at = date("Y-m-d H:i:s");
			if($message->save()){
				Yii::$app->session->setFlash('messageSent');
			}			
		}
	}
	
	
	public function actionSendMessageWishesContactDetails(){
		
		$from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		$wish_id = \Yii::$app->request->post()['wish_id'];
		
		$model = Wish::findOne($wish_id);
		
			$details ='<p>Contact Message</p>';						
				 if($model->show_mail_status == 1){ 
			$details .='<p>Mail to this address : <b>'.$model->show_mail.'</b></p>';
				 } 
				if(($model->show_mail_status == 1) && ($model->show_person_status == 1)){ 
			$details .='<p>or</p>';
				 }	
				if($model->show_person_status == 1){ 
			$details .='<p>In Person at this location :  <b>'.$model->show_person_location.' on '.$model->show_person_date.'</b></p>';
			     } 	
				if((($model->show_mail_status == 1) || ($model->show_person_status == 1)) && ($model->show_reserved_status == 1)){ 
				$details .='<p>or</p>';
				 }	
				 if($model->show_reserved_status == 1){ 
			$details .='<p>Reserved at this location : <b>'.$model->show_reserved_name.' , '.$model->show_reserved_location.' on '.$model->show_reserved_date.'</b></p>';
				 }	
				 if((($model->show_mail_status == 1) || ($model->show_person_status == 1) || ($model->show_reserved_status == 1) )&& ($model->show_other_status == 1)){ 
				$details .='<p>or</p>';
				 } 	
				 if($model->show_other_status == 1){ 
			$details .='<p>Other : <b>'.$model->show_other_specify.'</b></p>';
				 } 
				 
		$msg = $details;
		
		if($from != '' && $to != '' && $msg != ''){
			$message = new Message();
			$message->sender_id = $from;
			$message->recipient_id = $to;
			$message->parent_id = 0;
			$message->read_text = $to;
			$message->text = $msg;
			$message->created_at = date("Y-m-d H:i:s");
			if($message->save()){
				Yii::$app->session->setFlash('messageSent');
			}			
		}
	}
	
/* 	public function actionInbox(){
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$messages = $this->getThreads();
		$senduser = UserProfile::find()->where(['!=','user_id',\Yii::$app->user->id])->all();
		//print_r($messages);die;
		return $this->render('messages', 
						['user' => $user,
						 'profile' => $profile,
						 'messages' => $messages,
						 'senduser' => $senduser,
						]);		
	} */
	
 	/* public function getThreads(){
		//first send
		$threads = [];
		$send_messages = Message::find()->where(['sender_id'=>\Yii::$app->user->id])->orderBy('m_id DESC')->all();
		foreach($send_messages as $send_message){
			if(!array_key_exists($send_message->recipient_id,$threads))
				$threads[$send_message->recipient_id] = [
					'm_id' => $send_message->m_id,
					'text' => $send_message->text,
					'created_at' => $send_message->created_at,
					'send_by' => $send_message->sender_id,
					'threads' => [
						[
						'm_id' => $send_message->m_id,
						'text' => $send_message->text,
						'created_at' => $send_message->created_at,
						'send_by' => $send_message->sender_id,]
						]
				];
			else
				$threads[$send_message->recipient_id]['threads'][] = [
					'm_id' => $send_message->m_id,
					'text' => $send_message->text,
					'created_at' => $send_message->created_at,
					'send_by' => $send_message->sender_id
				];
		}
		$recievd_messages = Message::find()->where(['recipient_id'=>\Yii::$app->user->id])->orderBy('m_id DESC')->all();
		foreach($recievd_messages as $message){
			if(!array_key_exists($message->sender_id,$threads))
				$threads[$message->sender_id] = [
					'm_id' => $message->m_id,
					'text' => $message->text,
					'created_at' => $message->created_at,
					'send_by' => $message->sender_id,
					'threads' => [[
						'm_id' => $message->m_id,
						'text' => $message->text,
						'created_at' => $message->created_at,
						'send_by' => $message->sender_id,]]
				];
			else
				$threads[$message->sender_id]['threads'][] = [
					'm_id' => $message->m_id,
					'text' => $message->text,
					'created_at' => $message->created_at,
					'send_by' => $message->sender_id,
				];
		}
		arsort($threads);
		return $threads;
	}  */
	

	
/* 	public function actionSendResetPassword(){
		if(isset(\Yii::$app->request->post()['email'])){
			$user = User::find()->where(['email'=>\Yii::$app->request->post()['email']])->one();
			if($user != null){
				$user->generatePasswordResetToken();
				$user->save();
				
				Yii::$app->mailer->compose('password-reset',['key'=>$user->password_reset_token]) // a view rendering result becomes the message body here
				->setFrom(Yii::$app->params['adminEmail'])
				->setTo($user->email)
				->setSubject('SimplyWishes : Reset Your Password')
				->send();
			}
		}
	}
	public function actionResetPassword(){
		
	} */
	/*     // Friend Function is hide 
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
	} */
	
	
		
	public function actionMyFriend($findfriends=""){
		$user = User::findOne(\Yii::$app->user->id);		
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$followlist = [];
		if($findfriends)
		{
			/*$myfollow =  \app\models\Userprofile::find()->select(['user_id'])->where(['!=','user_id1',\Yii::$app->user->id])->andWhere(['or',['like','firstname',$findfriends],['like','lastname',$findfriends] ])->all(); */
			
			
			$myfollow =  \app\models\Userprofile::find()->select(['user_id'])->where(['!=','user_id',\Yii::$app->user->id])->andWhere(['like',"CONCAT(firstname, ' ', lastname)",$findfriends] )->all();
			
			$foll = FollowRequest::find()->select("requested_to")->where(["requested_by"=>\Yii::$app->user->id])->all();
			if($foll)
			{
				foreach( $foll  as $tmp )
					array_push($followlist,$tmp->requested_to);
			}
		} else {
			$myfollow = FollowRequest::find()->where(["requested_by"=>\Yii::$app->user->id])->all();
			$foll = FollowRequest::find()->select("requested_to")->where(["requested_by"=>\Yii::$app->user->id])->all();	
			if($foll)
			{
				foreach( $foll  as $tmp )
					array_push($followlist,$tmp->requested_to);
			}			
		}
		
		return $this->render('my_follow', 
						[
						 'user' => $user,	
						 'profile' => $profile,
						 'myfollow' => $myfollow,
						 'findfriends'=>$findfriends,
						 'followlist'=>$followlist,
						]);		
	}
	
	
	public function actionFriendRequested(){
		$user = User::findOne(\Yii::$app->user->id);		
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$myfriend = FriendRequest::find()->Where(["requested_to"=>\Yii::$app->user->id])->andWhere(["status"=>0])->orderBy("f_id DESC")->all();	
		
		return $this->render('my_friend_requested', 
						[
						 'user' => $user,	
						 'profile' => $profile,
						 'myfriend' => $myfriend,
						]);		
	}
	
	
	public function actionMyFollow(){
		$user = User::findOne(\Yii::$app->user->id);		
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$myfriend = FriendRequest::find()->where(["requested_by"=>\Yii::$app->user->id])->orWhere(["requested_to"=>\Yii::$app->user->id])->andWhere(["status"=>1])->all();	
		
		return $this->render('my_follow', 
						[
						 'user' => $user,	
						 'profile' => $profile,
						 'myfriend' => $myfriend,
						]);		
	}
	
	public function actionInboxMessage(){
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		$messages = $this->getInboxThreads();
		/* $messages = Message::find()->where(['recipient_id'=>\Yii::$app->user->id,'recipient_del' => 0])->orderBy('m_id DESC')->all(); */
		

			
		$senduser = UserProfile::find()->where(['!=','user_id',\Yii::$app->user->id])->all();
		//print_r($messages);die;
		return $this->render('inbox_messages', 
						['user' => $user,
						 'profile' => $profile,
						 'messages' => $messages,
						 'senduser' => $senduser,
						]);		
	}
	
	public function actionSentMessage(){
		$user = User::findOne(\Yii::$app->user->id);
		$profile = UserProfile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
		//$messages = Message::find()->where(['sender_id'=>\Yii::$app->user->id,'sender_del'=>0])->orderBy('m_id DESC')->all();
		$messages = $this->getSentThreads();
		
		$senduser = UserProfile::find()->where(['!=','user_id',\Yii::$app->user->id])->all();
		//print_r($messages);die;
		
		return $this->render('sent_messages', 
						['user' => $user,
						 'profile' => $profile,
						 'messages' => $messages,
						 'senduser' => $senduser
						]);		
	}
	
	/**
     * Multiple Deletes an existing User for company admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	 
	 public function actionMultiDeleteSendMessage()
	 {    
			$msg_id = Yii::$app->request->post()['msg_id'];
			
			if($msg_id)
			{
				 foreach($msg_id as $tmp)
				 {					
					$message =  Message::find()->where(['m_id'=>$tmp ])->andwhere(['OR',['recipient_id'=>\Yii::$app->user->id],['sender_id'=>\Yii::$app->user->id]])->one();
					if($message)
					{
						/* if($message->reply_sender_id == \Yii::$app->user->id )
							$message->sender_del = 1;
						else if ($message->reply_recipient_id == \Yii::$app->user->id )
							$message->recipient_del = 1;							
						else 							
							$message->sender_del = 1;
						 */
						if(!empty($message->delete_status))
						{
							$message->delete_status = $message->delete_status.",".\Yii::$app->user->id.",";
						} else 
						{
							$message->delete_status = ",".\Yii::$app->user->id.",";
						}
						
						$message->save(); 						
					}
				 }
			}  			
		}
		
	public function actionDeleteSendMessage()
	 {    
			$msg_id = Yii::$app->request->post()['msg_id'];	
			if($msg_id)
			{							
				$message =  Message::find()->where(['m_id'=>$msg_id ])->andwhere(['OR',['recipient_id'=>\Yii::$app->user->id],['sender_id'=>\Yii::$app->user->id]])->one();
				
				if($message)
				{
					/* if($message->reply_sender_id == \Yii::$app->user->id )
						$message->sender_del = 1;
					else if ($message->reply_recipient_id == \Yii::$app->user->id )
						$message->recipient_del = 1;							
					else 							
						$message->sender_del = 1; */
					
						if(!empty($message->delete_status))
						{
							$message->delete_status = $message->delete_status.",".\Yii::$app->user->id.",";
						} else 
						{
							$message->delete_status = ",".\Yii::$app->user->id.",";
						}
						
						$message->save(); 						
				}				 
			}  			
		}

		
		/**
     * Multiple Deletes an existing User for company admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
	 
	 public function actionMultiDeleteInboxMessage()
	 {    
			$msg_id = Yii::$app->request->post()['msg_id'];	
				
			if($msg_id)
			{
				 foreach($msg_id as $tmp)
				 {					
					$message =  Message::find()->where(['m_id'=>$tmp])->andwhere(['OR',['recipient_id'=>\Yii::$app->user->id],['sender_id'=>\Yii::$app->user->id]])->one();
					if($message)
					{
						
						if(!empty($message->delete_status))
						{
							$message->delete_status = $message->delete_status.",".\Yii::$app->user->id.",";
						} else 
						{
							$message->delete_status = ",".\Yii::$app->user->id.",";
						}
						
						$message->save(); 						
					}
				 }
			}  		
			
		}
		
	public function actionDeleteInboxMessage()
	 {    
			$msg_id = Yii::$app->request->post()['msg_id'];	
			if($msg_id)
			{
				$message =  Message::find()->where(['m_id'=>$msg_id ])->andwhere(['OR',['recipient_id'=>\Yii::$app->user->id],['sender_id'=>\Yii::$app->user->id]])->one();
				
				if($message)
				{	
						 
						 
						if(!empty($message->delete_status))
						{
							$message->delete_status = $message->delete_status.",".\Yii::$app->user->id.",";
						} else 
						{
							$message->delete_status = ",".\Yii::$app->user->id.",";
						}
						
					
						$message->save(); 						
				}				 
			}  			
		}

		
   public function actionReadInboxMessage()
	 {    
			$msg_id = Yii::$app->request->post()['msg_id'];	
			if($msg_id)
			{											
				$message =  Message::find()->where(['m_id'=>$msg_id ])->andwhere(['Or',['recipient_id'=>\Yii::$app->user->id],['reply_recipient_id'=>\Yii::$app->user->id]])->one();
				
				
				if($message)
				{
						$message->read_text = 1;
						$message->save(); 						
				}				 
			}  			
		}
	 
	 
	 public function getInboxThreads(){
		//first send
		$threads = [];
		
	 $deleteduser = ",".\Yii::$app->user->id.",";	
	 $inbox_messages = Message::find()->where(['parent_id'=>0])->andwhere(['NOT LIKE','delete_status',$deleteduser ])->andwhere(['OR',['reply_recipient_id' => \Yii::$app->user->id],['reply_sender_id' => \Yii::$app->user->id],['and',['recipient_id'=>\Yii::$app->user->id],['reply_recipient_id' => 0 ]]])->orderBy('created_at DESC')->all();  
	
	
		foreach($inbox_messages as $messages){
			
		$thread_messages = Message::find()->where(['parent_id' => $messages->m_id ])->orderBy('m_id DESC')->all();
		
		$threadsmsg = [];
		if(isset($thread_messages) && !empty($thread_messages))
		  {				
					foreach($thread_messages as $messages2){									
								$threadsmsg[$messages2->m_id] = [
									    
									'm_id' => $messages2->m_id,
									'text' => $messages2->text,
									'created_at' => $messages2->created_at,
									'send_by' => $messages2->sender_id,								
										
									];																
						}	
					
		  } 
		$other_id = $messages->sender_id;
		if($messages->reply_recipient_id != 0)
		{
			
			if($messages->reply_recipient_id != \Yii::$app->user->id )
				$other_id = $messages->reply_recipient_id;
			else if($messages->reply_sender_id != \Yii::$app->user->id )
				$other_id = $messages->reply_sender_id;
		}			
		
		  $threads[$messages->m_id] = [	
			'm_id' => $messages->m_id,
		    'read_text' => $messages->read_text,
		    'text' => $messages->text,
			'created_at' => $messages->created_at,
			'sender_id' => $messages->sender_id,
			'recipient_id'=> $other_id,
			];	
			
			if(isset($thread_messages) && !empty($thread_messages))
			{	
				$threads[$messages->m_id]['threads'] = $threadsmsg;
			}
			
		}
		
		return $threads;
	} 
	
	
	public function getSentThreads(){
		
		$threads = [];
		
			$deleteduser = ",".\Yii::$app->user->id.",";	
		
		  $sent_messages = Message::find()->where(['parent_id'=>0])->andwhere(['NOT LIKE','delete_status',$deleteduser ])->andwhere(['OR',['reply_recipient_id' => \Yii::$app->user->id],['reply_sender_id' => \Yii::$app->user->id],['and',['sender_id'=>\Yii::$app->user->id],['reply_sender_id' => 0 ]]])->orderBy('created_at DESC')->all();  
		  
		
		foreach($sent_messages as $messages){
			
		$thread_messages = Message::find()->where(['parent_id' => $messages->m_id ])->orderBy('m_id DESC')->all();
		
		$threadsmsg = [];
		if(isset($thread_messages) && !empty($thread_messages))
		 {				
					foreach($thread_messages as $messages2){									
								$threadsmsg[$messages2->m_id] = [
									    
									'm_id' => $messages2->m_id,
									'text' => $messages2->text,
									'created_at' => $messages2->created_at,
									'send_by' => $messages2->sender_id,
										
									];																
						}	 			
		  } 
			
		$other_id = $messages->sender_id;
		if($messages->reply_recipient_id != 0)
		{
			
			if($messages->reply_recipient_id != \Yii::$app->user->id )
				$other_id = $messages->reply_recipient_id;
			else if($messages->reply_sender_id != \Yii::$app->user->id )
				$other_id = $messages->reply_sender_id;
		}	
		
		  $threads[$messages->m_id] = [	
			'm_id' => $messages->m_id,
		    'read_text' => $messages->read_text,
		    'text' => $messages->text,
			'created_at' => $messages->created_at,
			'sender_id' =>$messages->recipient_id,	
			'recipient_id'=> $other_id,			
			];	
			
			if(isset($thread_messages) && !empty($thread_messages))
			{	
				$threads[$messages->m_id]['threads'] = $threadsmsg;
			}
			
		}
		 
		return $threads;
	} 
	
		public function actionSendMessageInbox(){
		
		$from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		$msg = \Yii::$app->request->post()['msg'];
		
		if($from != '' && $to != '' && $msg != ''){
			$message = new Message();
			$message->sender_id = $from;
			$message->recipient_id = $to;
			$message->parent_id = 0;
			$message->read_text = $to;
			$message->text = $msg;
			$message->created_at =  date("Y-m-d H:i:s");
			if($message->save()){
				Yii::$app->session->setFlash('messageSent');
			}
		}
	}
	
	public function actionReplyMessage()
	{		
		$from = \Yii::$app->request->post()['send_from'];
		$to = \Yii::$app->request->post()['send_to'];
		$msg = \Yii::$app->request->post()['msg'];
		$msg_id = \Yii::$app->request->post()['msg_id'];
		
		if($from != '' && $to != '' && $msg != ''){
			$message = new Message();
			$message->sender_id = $from;
			$message->recipient_id = $to;
			$message->parent_id = $msg_id;
			$message->text = $msg;
			$message->read_text = 0;
			$message->delete_status = "";
			$message->created_at = date("Y-m-d H:i:s");

			if($message->save()){
				
				$parentmessages = Message::find()->where(['m_id'=>$message->parent_id])->one();
				$parentmessages->reply_sender_id = $from;
				$parentmessages->reply_recipient_id = $to;
				$parentmessages->read_text = $to;
				$parentmessages->delete_status = "";
				$parentmessages->created_at = date("Y-m-d H:i:s");
				$parentmessages->save();
				
				return json_encode([
					'status'=>true
				]);
			}
			else return json_encode([
					'status'=>false
				]);
		}
	}
	
	

	
}