<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl('site/login');
?>
<div class="password-reset">

	<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($user->username)), $editmessage);		
		$editmessage = str_replace("##LOGINLINK##", Html::a(Html::encode('Click here'), $resetLink), $editmessage);		
		echo $editmessage;
	?>
	
    <!--<p>Hello <?php // Html::encode($user->username) ?>,</p>

    <p>Welcome to SimplyWishes, <br> You Got Grant For Your Wish </p>

    <p><?php // Html::a(Html::encode($resetLink), $resetLink) ?></p>-->
	
</div>
