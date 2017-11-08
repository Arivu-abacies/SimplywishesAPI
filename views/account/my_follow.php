<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\UserProfile;
use yii\jui\AutoComplete;
use yii\web\JsExpression;


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
	</ul>
	
       <div class="tab-content smp-mg-bottom">
		
		<div role="tabpanel" class="tab-pane active grid" id="fullfilledwish">
		
		<div class="input-group col-md-6" style="margin-bottom:10px" >
		<?php 
		 $data =  \app\models\Userprofile::find()->select(['CONCAT(firstname," ",lastname) as value'])->where(['!=','user_id',\Yii::$app->user->id])->groupBy('value')->asArray()->all();	
				
					echo AutoComplete::widget([
						'name' => 'searh_field',
						'id' => 'searh_field',
						'options' => ['class' => 'form-control','placeholder'=>'Search for your friends'],
						'value'	=> $findfriends,					
						'clientOptions' => [
							'source' => $data, 
							'autoFill'=>true,							 
								],
							]); 
		?>					
				<!--<input name="searh_field" id="searh_field" type="text" class="form-control" value="<?= $findfriends ?>" placeholder="Search for your friends">-->
				<span class="input-group-btn">
				  <button class="search-wish btn btn-default" type="button">
					<span class="glyphicon glyphicon-search"></span>
				  </button>
				</span>
		</div>
		
			<?php if(isset($myfollow) && !empty($myfollow))
			{				
			?>
		<div id="frienduser" name="frienduser" >	
		  <?php  
				foreach($myfollow as $user)
				{	
					if($findfriends)
						$userid = $user->user_id;
					else 
						$userid = $user->requested_to;
					
					$profile = UserProfile::find()->where(['user_id'=>$userid])->one();					
				?>
					 <div class="col-md-6 grid-item" id="parent_div_<?= $profile->user_id; ?>"> 
						<div class="smp_inline thumbnail friendprofile_img">
							<?php 
							if($profile->profile_image!='') 
								echo '<img  src="'.\Yii::$app->homeUrl.$profile->profile_image.'" class="img-responsive" alt="my-profile-Image">';
							else 
								echo '<img  src="'.\Yii::$app->homeUrl.'images/default_profile.png"   class="img-responsive" alt="my-profile-Image">';
								?>							
						</div>
						<div class="smp_inline">
							<p><span class="left-space"><?= Html::a($profile->firstname.' '.$profile->lastname, Url::to(['account/profile','id'=>$profile->user_id],true)) ?></span></p>						
							<p>
								<?php 
								  if(empty($findfriends))
								  {
								?>
									<span id="frd_<?=$profile->user_id  ?>" class="btn btn-danger pull-right remove-unfollow" for="<?php echo $profile->user_id ?>"><i class='fa fa-times fa-lg'></i> Unfollow</span>
									
								  <?php } else { ?>
									<?php 							
									  if(in_array($userid,$followlist))
									  {
									?>
									<span id="frd_<?=$profile->user_id  ?>" class="btn btn-danger pull-right followfrd" for="<?php echo $profile->user_id ?>"><i class='fa fa-times fa-lg'></i> Unfollow</span>
									<?php } else { ?>
									<span id="frd_<?=$profile->user_id  ?>" class="btn btn-success pull-right followfrd" for="<?php echo $profile->user_id ?>"><i class='fa fa-check fa-lg'></i> Follow</span>								
								  <?php }  } ?>
								  
								  
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
</div>

<script>
$(document).ready(function(){
	$(".remove-unfollow").click(function(){
		var check = confirm( "Are sure to Unfollow from the Follow List? ");
		if(check)
		{
			var request_id = $(this).attr("for");
			
			$.ajax({
				url : '<?=Url::to(['follow/cancel-follow'])?>',
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

	$(".followfrd").click(function(){		
		var send_from = <?= \Yii::$app->user->id ?>;		
		var send_to = $(this).attr("for");		
		var frd_id = $(this).attr("id");		
		$.ajax({
				url : '<?=Url::to(['follow/follow-request'])?>',
				type : 'POST',
				data : {
					send_from:send_from,
					send_to:send_to,
					},
				success: function(response){					
					if(response == 'follow')
					{
						$("#"+frd_id).removeClass("btn-success");
						$("#"+frd_id).addClass("btn-danger");
						$("#"+frd_id).html("<i class='fa fa-times fa-lg'></i> Unfollow ");	
					}
					else if(response == 'unfollow')
					{
						$("#"+frd_id).removeClass("btn-danger");
						$("#"+frd_id).addClass("btn-success");
						$("#"+frd_id).html("<i class='fa fa-check fa-lg'></i> Follow ");					
					}
				}
		}); 
	});
	
	//search srcipt
	$(".search-wish").on("click",function(){				
			var url = "<?=Url::to(['account/my-friend'])?>";
			window.location.href = url+"?findfriends="+$("input[name=searh_field]").val();					
	});
	
	$('#searh_field').keypress(function (e) {
		   if (e.which == 13) {
				$( ".search-wish" ).trigger( "click" );
		  } 
	});
	
</script>


