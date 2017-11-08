<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\FriendRequest;
use app\models\FollowRequest;
?>	

	<div class="col-md-12">
	
	<!-- start alert messages -->
    <?php if (Yii::$app->session->hasFlash('messageSent')): ?>
        <div class="alert alert-success">
            Your message has been sent successfully.
        </div>
	<?php endif; ?>
	<!-- end alert messages -->
	
	<h3 class="fnt-green" ><?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id)
				echo "My Profile";
			  else
				echo $profile->firstname."'s Profile"; 
			?>  
		<?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id): ?> 
			<a href="<?=\Yii::$app->homeUrl?>account/edit-account"><button class="btn btn-info">Edit Profile</button></a>
		<?php endif; ?>
	</h3>
	
		<div class="col-md-3">
			<div class="thumbnail">
			<?php 
			if($profile->profile_image!='') 
				echo '<img src="'.\Yii::$app->homeUrl.$profile->profile_image.'"  class="img-responsive const-img-size" alt="my-profile-Image">';
			else 
				echo '<img src="'.\Yii::$app->homeUrl.'images/default_profile.png"  class="img-responsive const-img-size" alt="my-profile-Image">';
			?>
			</div>
		</div>
		<div class="col-md-8">
			<div class="">
				<p><b>Name : </b><span><?=$profile->firstname." ".$profile->lastname?></span></p>
				<p><b>Location : </b><span><?=$profile->location?></span></p>
				<p><b>About Me : </b><span><?=$profile->about?> </span></p>
				<?php if (!Yii::$app->user->isGuest && $user->id != \Yii::$app->user->id){ ?>
				<a href="#messagemodal" data-toggle="modal"><button class="btn btn-warning">Send Me A Message</button></a>
				<?php } else if ($user->id != \Yii::$app->user->id){ ?>
				<a href="<?=\Yii::$app->homeUrl?>/site/login" data-toggle="modal"><button class="btn btn-warning">Send Me A Message</button></a>
				<?php } ?>
				<?php /* if (!Yii::$app->user->isGuest && $user->id != \Yii::$app->user->id){ 
							$checkfriendlist = FriendRequest::find()->where(["requested_by"=>\Yii::$app->user->id,"requested_to"=>$user->id])->orWhere(["requested_to"=>\Yii::$app->user->id,"requested_by"=>$user->id])->one();
							
							if(!$checkfriendlist)
								echo '<a class="btn btn-info friendrequest ">Add as Friend</a>';
							else if($checkfriendlist->status == 0 && $checkfriendlist->requested_by == \Yii::$app->user->id )
								echo '<a class="btn btn-info friendrequest ">Friend Request Sent</a>';
							else if($checkfriendlist->status == 0 && $checkfriendlist->requested_to == \Yii::$app->user->id )
								echo '<a id="accept_fnds" class="btn btn-info" for="'. $checkfriendlist->f_id.'">Accept</a>';
							else if($checkfriendlist->status == 1)
								echo '<a class="btn btn-success">Friends</a>';
							
						} */ ?>
						
				<?php if (!Yii::$app->user->isGuest && $user->id != \Yii::$app->user->id){ 
							$checkfollowlist = FollowRequest::find()->where(["requested_by"=>\Yii::$app->user->id,"requested_to"=>$user->id])->one();
							
							if(!$checkfollowlist)
								echo '<a class="btn btn-success followrequest ">Follow</a>';
							else if($checkfollowlist->status == 0)
								echo '<a class="btn btn-danger followrequest ">Unfollow</a>';
							
						} ?>		
				
			</div>
		</div>
	</div>
<!-- modal Starts -->
	<div class="modal fade" id="messagemodal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Message</h4>
		  </div>
		  <div class="modal-body">
			<div class="media">
			  <div class="media-left list-icon">
					<?php 
					if($profile->profile_image!='') 
						echo '<img  src="'.\Yii::$app->homeUrl.$profile->profile_image.'"  alt="my-profile-Image">';
					else 
						echo '<img  src="'.\Yii::$app->homeUrl.'images/default_profile.png" alt="my-profile-Image">';
					?>
				 <!--<img src="./images/man1.jpg" alt="">-->
			  </div>
			  <div class="media-body">
				<h4 class="media-heading"><?=$profile->firstname." ".$profile->lastname?></h4>
			  </div>
			</div>
			</br>
			<div class="form-group">
			<label for="message">Enter Your Message</label>
			<textarea class="msg form-control" rows="4"></textarea>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="send-msg btn btn-primary">Send</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<!-- modal Ends -->
	<script>
		$(".send-msg").on("click",function(){
			console.log($('.msg').val());
			var msg = $('.msg').val();
			if($.trim(msg) === "")
			{
				alert("Please check the message.");
				return false;
				
			}
			var send_to = "<?=$user->id?>";
			var send_from = "<?=\Yii::$app->user->id?>";
			$.ajax({
				url : '<?=Url::to(['account/send-message'])?>',
				type : 'POST',
				data : {msg:msg,send_from:send_from,send_to:send_to},
				success: function(response){
					console.log("response");
				}
			});
		});
	
	
	$(".friendrequest").on("click",function(){			
			var send_to = "<?=$user->id?>";
			var send_from = "<?=\Yii::$app->user->id?>";
			$.ajax({
				url : '<?=Url::to(['friend/friend-request'])?>',
				type : 'POST',
				data : {send_from:send_from,send_to:send_to},
				success: function(response){					
					console.log("response");
					$(".friendrequest").html("Friend Request Sent");					
				}
			});
		});
		
		
		
	$("#accept_fnds").on("click",function(){	
			var request_id = $(this).attr("for");
			if($.trim(request_id) == "")
				return false;
			 $.ajax({
				url : '<?=Url::to(['friend/request-accepted'])?>',
				type : 'POST',
				data : {requestid:request_id},
				success: function(response){					
					console.log("response");
					if(response == true)
					{					   					  
					 $("#accept_fnds").html("Friends");
					 $("#accept_fnds").removeAttr("for");
					 $("#accept_fnds").removeClass("btn-info");
					 $("#accept_fnds").addClass('btn-success');
					 
					}						
				}
			});		 
		});
		
		
		$(".followrequest").on("click",function(){			
			var send_to = "<?=$user->id?>";
			var send_from = "<?=\Yii::$app->user->id?>";
			$.ajax({
				url : '<?=Url::to(['follow/follow-request'])?>',
				type : 'POST',
				data : {send_from:send_from,send_to:send_to},
				success: function(response){					
					console.log("response");
					if($.trim(response) == "follow")
					{
						$(".followrequest").html("Unfollow");
						$( ".followrequest" ).removeClass( "btn-success" );
						$( ".followrequest" ).addClass( "btn-danger" );						
					}	
					else if($.trim(response) == "unfollow")	
					{
						$(".followrequest").html("Follow");	
						$( ".followrequest" ).removeClass( "btn-danger" );
						$( ".followrequest" ).addClass( "btn-success" );
					}
				}
			});
		});
		
	</script>