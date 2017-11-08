<?php if(Yii::$app->session->getFlash('success')!='') {?>
			<div class="alert alert-success" role="alert">
				<strong> <?= Yii::$app->session->getFlash('success'); ?>.</strong>
			</div>
	
		<?php } ?>
		
<div class="row page-header">
  <div class="container my-profile">
	<div class="col-md-12">
	
	
			<h3 class="fnt-green" style="margin-left:75px" >My Profile</h3>
		<div class="col-md-3">
	
			<div class="thumbnail">
				<?php 
			if($profile->profile_image!='') 
				echo '<img  src="'.\Yii::$app->homeUrl.$profile->profile_image.'"  class="img-responsive const-img-size" alt="my-profile-Image">';
			else 
				echo '<img  src="'.\Yii::$app->homeUrl.'images/default_profile.png"  class="img-responsive const-img-size" alt="my-profile-Image">';
			?>
			</div>
		</div>
		<div class="col-md-8">
			<div class="">
				<p><b>Name : </b><span><?=$profile->firstname." ".$profile->lastname?></span></p>
				<p><b>Location : </b><span><?=$profile->location?></span></p>
				<p><b>About Me : </b><span><?=$profile->about?> </span></p>	
			</div>
		</div>
	</div>
	<div class="col-md-12 smp-mg-bottom"></div>
	<div class="col-md-12 link-thumb-contain">
		
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>account/edit-account"><i class="fa fa-id-card fa-10x fnt-green" aria-hidden="true"></i>Account Info</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>wish/create"><i class="fa fa-pencil-square-o fa-10x fnt-pink" aria-hidden="true"></i>Add Wish</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>account/inbox-message"><i class="fa fa-comments-o fa-10x fnt-orange" aria-hidden="true"></i>Inbox</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>account/my-account"><i class="fa fa-tasks fa-10x fnt-blue" aria-hidden="true"></i>My Wishes</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>wish/my-drafts"><i class="fa fa-window-restore fa-10x fnt-grey" aria-hidden="true"></i>My Drafts</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>account/my-friend"><i class="fa fa-group fa-10x fnt-grn-yellow " aria-hidden="true"></i>Friends</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>happy-stories/my-story"><i class="fa fa-vcard-o fa-10x fnt-brown" aria-hidden="true"></i>My Happy Story</a>
		</div>
		<div class="col-md-3">
			<a href="<?=Yii::$app->homeUrl?>happy-stories/create"><i class="fa fa-newspaper-o fa-10x fnt-sea" aria-hidden="true"></i>Tell Your Story</a>
		</div>
	</div>
	<div class="col-md-12 smp-mg-bottom"></div>

  </div>
</div>

