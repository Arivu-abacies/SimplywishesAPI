<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl('site/login');
?>
<div class="password-reset">

	<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($user->username)), $editmessage);	
		
			$details ='';						
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

		$editmessage = str_replace("##MESSAGE##", nl2br($details), $editmessage);	
		$editmessage = str_replace("##LOGINLINK##", Html::a(Html::encode('Click here'), $resetLink), $editmessage);		
		echo $editmessage;
	?>
	
	
</div>
