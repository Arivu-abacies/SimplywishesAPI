<?php
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="password-reset">

	<?php 			
		$editmessage = str_replace("##USERNAME##", nl2br(Html::encode($username)), $editmessage);		
		echo $editmessage;
	?>
	
	
	
   <!-- <p>Hello  <?php // Html::encode($username) ?>,</p>
	<p>Thank you for contacting us. </p>
	<p>We will review your message and get back to you!</p> -->
	
</div>
