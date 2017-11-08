<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>
<div class="password-reset">


<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($user->username)), $editmessage);		
		$editmessage = str_replace("##LOGINLINK##", Html::a(Html::encode('Click here'), $loginLink), $editmessage);		
		echo $editmessage;
	?>

<!--	
Hello <?php // $user->username ?>,
<br>Your Password is Reset Successfully	
<br>
Thanks, 
<br>
<?php // $loginLink ?>
-->

</div>
