<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/user-validation', 'auth_key' => $user->auth_key]);
?>
<div class="password-reset">

	<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($user->username)), $editmessage);		
		$editmessage = str_replace("##VALIDATIONLINK##", Html::a(Html::encode('Click here'), $resetLink), $editmessage);		
		echo $editmessage;
	?>



	
</div>
