<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\FriendRequest;
?>	

	<div class="col-md-12">
	
	<!-- start alert messages -->
    <?php if (Yii::$app->session->hasFlash('messageSent')): ?>
        <div class="alert alert-success">
            Your message has been sent successfully.
        </div>
	<?php endif; ?>
	<!-- end alert messages -->
	
	<h3 class="fnt-green" style="margin-left:75px" ><?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id)
				echo "My Profile";
			  else
				echo $profile->firstname."'s Profile"; 
			?>  
		<?php /* if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id): ?> 
			<a href="<?=\Yii::$app->homeUrl?>account/edit-account"><button class="btn btn-info">Edit Profile</button></a>
		<?php endif; */?>
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
		
		<!--<div class="col-md-8">
			<div class="">
				<p>Name : <span><?=$profile->firstname." ".$profile->lastname?></span></p>
				<p>Location : <span><?=$profile->location?></span></p>
				<p>About Me : <span><?=$profile->about?> </span></p>
				<?php if (!Yii::$app->user->isGuest && $user->id != \Yii::$app->user->id){ ?>
				<a href="#messagemodal" data-toggle="modal"><button class="btn btn-warning">Send Me A Message</button></a>
				<?php } else if ($user->id != \Yii::$app->user->id){ ?>
				<a href="<?=\Yii::$app->homeUrl?>/site/login" data-toggle="modal"><button class="btn btn-warning">Send Me A Message</button></a>
				<?php } ?>
				<?php if (!Yii::$app->user->isGuest && $user->id != \Yii::$app->user->id){ 
							$checkfriendlist = FriendRequest::find()->where(["requested_by"=>\Yii::$app->user->id,"requested_to"=>$user->id])->orWhere(["requested_to"=>\Yii::$app->user->id,"requested_by"=>$user->id])->one();
							
							if(!$checkfriendlist)
								echo '<a class="btn btn-info friendrequest ">Add as Friend</a>';
							else if($checkfriendlist->status == 0 && $checkfriendlist->requested_by == \Yii::$app->user->id )
								echo '<a class="btn btn-info friendrequest ">Friend Request Sent</a>';
							else if($checkfriendlist->status == 0 && $checkfriendlist->requested_to == \Yii::$app->user->id )
								echo '<a id="accept_fnds" class="btn btn-info" for="'. $checkfriendlist->f_id.'">Accept</a>';
							else if($checkfriendlist->status == 1)
								echo '<a class="btn btn-success">Friends</a>';
							
						} ?>
				
			</div>
		</div>-->
	<?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id)
			{	
			?> 
			
				
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>account/edit-account"><i class="fa fa-id-card fa-6x fnt-green" aria-hidden="true"></i>Account Info</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>wish/create"><i class="fa fa-pencil-square-o fa-6x fnt-pink" aria-hidden="true"></i>Add Wish</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>account/inbox-message"><i class="fa fa-comments-o fa-6x fnt-orange" aria-hidden="true"></i>Inbox</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>account/my-account"><i class="fa fa-tasks fa-6x fnt-blue" aria-hidden="true"></i>My Wishes</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>wish/my-drafts"><i class="fa fa-window-restore fa-6x fnt-grey" aria-hidden="true"></i>My Drafts</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>account/my-friend"><i class="fa fa-group fa-6x fnt-grn-yellow " aria-hidden="true"></i>Friends</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>happy-stories/my-story"><i class="fa fa-vcard-o fa-6x fnt-brown" aria-hidden="true"></i>My Happy Story</a>
				</div>
				<div class="col-md-6 link-thumb">
					<a href="<?=Yii::$app->homeUrl?>happy-stories/create"><i class="fa fa-newspaper-o fa-6x fnt-sea" aria-hidden="true"></i>Tell Your Story</a>
				</div>
				
			<?php } ?>		
	</div>
<!-- modal Starts -->
	<!--<div class="col-md-9">
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
							echo '<img src="'.\Yii::$app->homeUrl.$profile->profile_image.'"  alt="my-profile-Image">';
						else 
							echo '<img src="'.\Yii::$app->homeUrl.'images/default_profile.png" alt="my-profile-Image">';
						?>
		
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
</div>	

	<!-- modal Ends -->

	
	<script>
		$(".send-msg").on("click",function(){
			console.log($('.msg').val());
			var msg = $('.msg').val();
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
		
	</script>