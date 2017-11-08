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

    <div class="alert alert-success" style="display:none">
            <span><?= ucfirst($profile->firstname)." ".ucfirst($profile->lastname) ?></span> and <span id="alert-name" ></span> are friends now.
    </div>
		
<div class="site-contact">
		<?php echo $this->render('_profilenew',['user'=>$user,'profile'=>$profile])?>
<div class="col-md-8" >		
	<ul class="nav nav-tabs smp-mg-bottom" role="tablist">
		<li role="presentation" >
			<a href="<?=\Yii::$app->homeUrl?>account/my-friend" role="tab" >Friends</a>
		</li>
	  <li role="presentation" class="active">
		 <a  role="tab" >Friend Requests</a>
	  </li>
	  <!--<li role="presentation">
		 <a href="<?=\Yii::$app->homeUrl?>account/my-follow" role="tab" >Following</a>
	  </li>-->
	</ul>
	
	<div class="tab-content smp-mg-bottom">
		<div role="tabpanel" class="tab-pane" id="activewish">
		</div>
		<div role="tabpanel" class="tab-pane active grid" id="fullfilledwish">
			<?php if(isset($myfriend) && !empty($myfriend))
				{
				foreach($myfriend as $userid)
				{	
					if($userid->requested_by == \Yii::$app->user->id)
						$user_id = $userid->requested_to;
					else
						$user_id = $userid->requested_by;	
					
					$frd_profile = UserProfile::find()->where(['user_id'=>$user_id])->one();					
				?>

			 <div class="col-md-6 grid-item" id="parent_div_<?= $userid->f_id; ?>" > 
				<div class="smp_inline thumbnail">
					<?php 
					if($frd_profile->profile_image!='') 
						echo '<img  src="'.\Yii::$app->homeUrl.$frd_profile->profile_image.'" class="img-responsive" alt="my-profile-Image">';
					else 
						echo '<img  src="'.\Yii::$app->homeUrl.'images/default_profile.png"   class="img-responsive" alt="my-profile-Image">';
						?>	
						
				</div>
				<div class="smp_inline">
					<p><span id="name_<?= $userid->f_id; ?>"  for="<?= $frd_profile->firstname.' '.$frd_profile->lastname ?>" ><?= Html::a($frd_profile->firstname.' '.$frd_profile->lastname, Url::to(['account/profile','id'=>$frd_profile->user_id],true)) ?></span></p>						
					<p><a class="acceptfriendrequest" for="<?= $userid->f_id; ?>" ><span class="btn btn-info pull-right" >Accept</span></a>	</p>
				</div>
			 </div>
			  
			<?php } } else {
				echo "Sorry, No new Friend Requested.";
			} ?>	
		</div>
	  </div>
		

	</div>
</div>
</div>

	<script>

	$(".acceptfriendrequest").on("click",function(){	
			var request_id = $(this).attr("for");
			var friend_name = $("#name_"+request_id).attr("for");
			$.ajax({
				url : '<?=Url::to(['friend/request-accepted'])?>',
				type : 'POST',
				data : {requestid:request_id},
				success: function(response){					
					console.log("response");
					if(response == true)
					{					   					  
					   $("#parent_div_"+request_id).remove();
					   $("#alert-name").html(friend_name);
					   $(".alert").show();
					} else{
					   $(".alert").hide();
					}						
				}
			});
			
			var listcount = $(".grid .grid-item").length;
			 if( parseInt($.trim(listcount)) <= 1 ){
					$(".grid").html("Sorry, No new Friend Requested."); 
				} 
		
		});
		
	</script>
	

