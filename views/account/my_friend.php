<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\UserProfile;


$this->title = 'My Friends';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
		<?php echo $this->render('_profilenew',['user'=>$user,'profile'=>$profile])?>
<div class="col-md-8" >		
	<ul class="nav nav-tabs smp-mg-bottom" role="tablist">
		<li role="presentation" class="active">
			<a href="#activewish" role="tab" data-toggle="tab">Friends</a>
		</li>
	  <li role="presentation">
		 <a href="<?=\Yii::$app->homeUrl?>account/friend-requested" role="tab" >Friend Requests</a>
	  </li>
	  <!--<li role="presentation">
		 <a href="<?=\Yii::$app->homeUrl?>account/my-follow" role="tab" >Following</a>
	  </li>-->
	</ul>
	
       <div class="tab-content smp-mg-bottom">
		
		<div role="tabpanel" class="tab-pane active grid" id="fullfilledwish">
			<?php if(isset($myfriend) && !empty($myfriend))
			{
				foreach($myfriend as $user)
				{	
					if($user->requested_by == \Yii::$app->user->id)
						$userid = $user->requested_to;
					else
						$userid = $user->requested_by;	
					
					$profile = UserProfile::find()->where(['user_id'=>$userid])->one();					
				?>
			 <div class="col-md-6 grid-item" id="parent_div_<?= $user->f_id; ?>"> 
				<div class="smp_inline thumbnail">
					<?php 
					if($profile->profile_image!='') 
						echo '<img  src="'.\Yii::$app->homeUrl.$profile->profile_image.'" class="img-responsive" alt="my-profile-Image">';
					else 
						echo '<img  src="'.\Yii::$app->homeUrl.'images/default_profile.png"   class="img-responsive" alt="my-profile-Image">';
						?>							
				</div>
				<div class="smp_inline">
					<p><span><?= Html::a($profile->firstname.' '.$profile->lastname, Url::to(['account/profile','id'=>$profile->user_id],true)) ?></span></p>						
					<p><span class="btn btn-smp-green pull-left" ><i class='fa fa-check fa-lg'></i> Friends</span>	
						<span class="btn btn-warning pull-right remove-unfriend" for="<?php echo $user->f_id ?>"><i class='fa fa-times fa-lg'></i> Unfriend</span>
					</p>
				</div>
			</div>
			  
			<?php } } else {
				echo "Sorry, No more friends.";
			} ?>
        </div>
       </div>

   </div>
</div>
</div>

<script>
$(document).ready(function(){
	$(".remove-unfriend").click(function(){
		var check = confirm( "Are sure to Un-friend from the Friend List? ");
		if(check)
		{
			var request_id = $(this).attr("for");
			
			$.ajax({
				url : '<?=Url::to(['friend/cancel-friend'])?>',
				type : 'POST',
				data : {requestid:request_id},
				success: function(response){					
					console.log("response");
					if(response == true)
					{					   					  
					   $("#parent_div_"+request_id).remove();					
					}						
				}
			});
			
			
		} else {
			return false;
		}
	});
});
</script>


