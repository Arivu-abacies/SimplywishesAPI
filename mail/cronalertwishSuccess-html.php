<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

//$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['wish/view', 'id' => $wish_id]);
?>
<div class="password-reset">

	<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($user->username)), $editmessage);		
		$editmessage = str_replace("##WISHNAME##", Html::a(Html::encode($wish_title)), $editmessage);		
		$editmessage = str_replace("##WISHLINK##", Html::a(Html::encode('Click here'), $resetLink), $editmessage);		
		echo $editmessage;
	?>
	
	
</div>
