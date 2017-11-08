<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">


	<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($user->username)), $editmessage);		
		$editmessage = str_replace("##RESTLINK##", Html::a(Html::encode('Reset here'), $resetLink), $editmessage);		
		echo $editmessage;
	?>
	
   <!-- <p>Hello <?php // Html::encode($user->username) ?>,</p>
    <p>Follow the link below to reset your password:</p>
    <p><?php // Html::a(Html::encode($resetLink), $resetLink) ?></p>-->
	
	
</div>
