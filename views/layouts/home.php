<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<title>SimplyWishes</title>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
	<?= Html::csrfMetaTags() ?>
	<link rel="shortcut icon" type="image/png" href="<?=Yii::$app->homeUrl?>images/favicon.png"/>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
	<?php $this->beginBody() ?>
	<!--***** Header Starts*****-->
	<div class="smp-head" style="top:0px;" >
		<div class="container">
			<div class="col-md-4 smp-logo" style="width: 29%;" >
				<a href="<?=Yii::$app->homeUrl?>"><img src="<?=Yii::$app->homeUrl?>images/logo.png" ></a>
			</div>
			<div class="col-md-8" style="width: 70.667%;>

				<div class="row" style="padding:4px 0px;">		
				</div>

				<hr style="border-color:#1085bf;">
				<nav class="navbar navbar-inverse">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>                        
							</button>
						</div>
						<div class="collapse navbar-collapse" id="myNavbar">
							<ul class="nav nav-pills smp-pills">
								<li data-id="home"><a href="<?=Yii::$app->homeUrl?>site/index#home">Home</a></li>
								<li data-id="abt"><a href="<?=Yii::$app->homeUrl?>site/about#abt">About Us</a></li>
								<li data-id="search_wish"><a href="<?=Yii::$app->homeUrl?>wish/index#search_wish">Find a Wish</a></li>

								<li data-id="top_wishers"><a href="<?=Yii::$app->homeUrl?>wish/top-wishers#top_wishers">iWish</a></li>
								<li data-id="i_grant"><a href="<?=Yii::$app->homeUrl?>wish/top-granters#i_grant">iGrant</a></li>
								<li data-id="i_wish"><a href="<?=Yii::$app->homeUrl?>happy-stories/index#i_wish">Happy Stories</a></li>
								<li data-id="edt"> 
									<?php if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin')){ ?>
										<a href="<?=Yii::$app->homeUrl?>editorial/index#edt">Editorial</a>
										<?php } else { ?> 
										<a href="<?=Yii::$app->homeUrl?>editorial/editorial#edt">Editorial</a>
										<?php } ?>			
									</li>

									<?php if(!\Yii::$app->user->isGuest){  ?>
										<li data-id="edt_home" class="dropdown" class="active"><a href="<?=Yii::$app->homeUrl?>site/index-home#edt_home" >Hello,<?php echo substr(\Yii::$app->user->identity->username,0,5)?>..!</a>
											<ul class="dropdown-menu nav nav-stacked">
												<li><a href="<?=Yii::$app->homeUrl?>wish/create"><i class="fa fa-clone fa-lg"></i>Add a Wish</a></li>
												<li><a href="<?=Yii::$app->homeUrl?>account/inbox-message"><i class="fa fa-inbox fa-lg"></i> Inbox</a></li>
												<li><a href="<?=Yii::$app->homeUrl?>account/my-account"><i class="fa fa-heart fa-lg"></i>My Wishes</a></li>
												<li><a href="<?=Yii::$app->homeUrl?>wish/my-drafts"><i class="fa fa-window-restore fa-lg"></i>My Drafts</a></li>

												<li><a href="<?=Yii::$app->homeUrl?>account/my-friend"><i class="fa fa-users fa-lg"></i>Friends</a></li>
												<li><a href="<?=Yii::$app->homeUrl?>account/my-saved"><i class="fa fa-save fa-lg"></i>Saved Wishes</a></li>
												<li><a href="<?=Yii::$app->homeUrl?>happy-stories/create"><i class="fa fa-commenting-o fa-lg"></i>Tell Your Story</a></li>
												<li><a href="<?=Yii::$app->homeUrl?>happy-stories/my-story"><i class="fa fa-smile-o fa-lg"></i>My Happy Story</a></li>
												<?php if(isset(\Yii::$app->user->identity->role) && (\Yii::$app->user->identity->role == 'admin')){ ?>
													<li><a href="<?=Yii::$app->homeUrl?>happy-stories/permission"><i class="fa fa-list-alt fa-lg"></i>Stories Approval</a></li>
													<li><a href="<?=Yii::$app->homeUrl?>mail-content/index"><i class="fa fa-clipboard fa-lg"></i>Mail Content</a></li>
					<!-- <li><a href="<?=Yii::$app->homeUrl?>wish/report-action"><i class="fa fa-flag-checkered fa-lg" ></i>
					Report Action</a></li> -->
					<?php } ?>
					<li><a href="<?=Yii::$app->homeUrl?>account/edit-account"><i class="fa fa-user-circle-o fa-lg"></i> Account Info</a></li>
					<li><a href="<?=Yii::$app->homeUrl?>site/setting-page"><i class="fa fa-cogs fa-lg"></i></i>Settings</a></li>
					<li><a href="#" >
						<?php  echo Html::beginForm(['/site/logout'], 'post')
						. Html::submitButton(
							'<i class="fa fa-sign-out fa-lg"></i>Logout',
							['class' => 'a-button']
							)
							. Html::endForm();  ?>
						</a></li>	
					</ul>			
				</li>
				<?php } else { ?>		
				<li data-id="edt_home" class="dropdown" class="active">
					<div class="btn-group pull-right btngroup">
						<a class="login" href="<?=Yii::$app->homeUrl?>site/login"><button class="btn btn-smp-blue smpl-brdr-left" type="button">
							Login
						</button></a>
						<a class="join" href="<?=Yii::$app->homeUrl?>site/sign-up"><button class="btn btn-smp-green smpl-brdr-right" type="button">
							Join Today
						</button></a>
					</div>
				</li>
				<?php } ?>
			</ul>
		</div>
	</nav>
</div>
</div>
</div>
<!--***** Header Ends*****-->
<?php if(!\Yii::$app->user->isGuest){  ?>
	<div class="banner-home" style="background-image:url('<?=Yii::$app->homeUrl?>images/login_banner.jpg');">	

		<div class="container" style="padding: 25px 0px 50px 36px;">

			<h1 class="slide_header">Make Someone </br>
				Happy Today</h1>  

			</div>

		</div>
		<?php } else { ?> 
		<div class="banner-home" style="background-image:url('<?=Yii::$app->homeUrl?>images/bgimage.jpg');">	

			<div class="container" style="padding: 25px 0px 50px 36px;">

				<h1 class="slide_header">Make Someone </br>
					Happy Today</h1>  	
					<a href="<?=Yii::$app->homeUrl?>site/sign-up"><button class="btn btn-smp-orange smpl-brdr" type="button">JOIN TODAY!</button></a>

				</div>

			</div>

			<?php } ?>

			<div class="container">			
				<!--<div  class="webShareIcons" data_text="SimplyWishes" data_url="<?= Url::to([''],true); ?>"></div>-->
				<div class="pull-right" style="margin-top:5px" >
					<a style="text-decoration: none;" href="https://www.facebook.com/SimplyWishescom-1121671277927963/" target="_blank">
						<img class="shareicon-home" style="width:35px" src="<?=Yii::$app->homeUrl?>images/icon/facebook.png" alt="Facebook" />
					</a>	
					<a style="text-decoration: none;"  href="https://plus.google.com/u/0/105910024848420550192" target="_blank">
						<img class="shareicon-home" style="width:30px" src="<?=Yii::$app->homeUrl?>images/icon/Google-plus.png" alt="Google-plus" />
					</a>
					<a style="text-decoration: none;"  href="https://www.instagram.com/simplywishes2016" target="_blank">
						<img class="shareicon-home" style="width:35px" src="<?=Yii::$app->homeUrl?>images/icon/instagram.png" alt="Instagram" />
					</a>	
					<a style="text-decoration: none;"  href="https://www.linkedin.com/in/simply-wishes/" target="_blank">
						<img  class="shareicon-home" style="width:40px" src="<?=Yii::$app->homeUrl?>images/icon/Linkedin.png" alt="Linkedin" />
					</a>
					<a style="text-decoration: none;"  href="https://www.pinterest.com/simplywishe5244/" target="_blank">
						<img  class="shareicon-home" style="width:30px" src="<?=Yii::$app->homeUrl?>images/icon/Pinterest.png" alt="Pinterest" />
					</a>	
					<a style="text-decoration: none;"  href="https://www.reddit.com/user/simplywishes/" target="_blank">
						<img class="shareicon-home" style="width:30px" src="<?=Yii::$app->homeUrl?>images/icon/reddit.png" alt="Reddit" />
					</a>
					<a style="text-decoration: none;"  href="https://twitter.com/simply_wishes" target="_blank">
						<img class="shareicon-home" style="width:40px" src="<?=Yii::$app->homeUrl?>images/icon/twitter.png" alt="Twitter" />
					</a>	
					<a style="text-decoration: none;"  href="https://www.youtube.com/channel/UC9oY1A49aO1ZQxjdGyJ3Z3Q" target="_blank">
						<img class="shareicon-home" style="width:30px" src="<?=Yii::$app->homeUrl?>images/icon/youtube-icon.png" alt="Youtube" />
					</a>

				</div>
				<br>

<!--

<a href="http://reddit.com/submit?url=http://referpy.com &amp; title=Logisieqa login" target="_blank">
        <img src="//www.redditstatic.com/spreddit1.gif" alt="Reddit" />
</a>

-->

<br>
<?=$content?>	
</div>
<!--***** Footer Starts*****-->
<div class="smp-foot">
	<footer class="container-fluid">
		<div class="col-md-12">
			<div class="col-md-4">
				<p> &copy; SimplyWishes 2017, All Rights Reserved <?php echo "home"; ?></p>
			</div>
			<div class="col-md-8">
				<ul class="smp-footer-links">
					<a href="<?=\Yii::$app->homeUrl?>page/view?id=1"><li>Privacy Policy</li></a>
					<a href="<?=\Yii::$app->homeUrl?>page/view?id=2"><li>Terms Of Use</li></a>
					<a href="<?=\Yii::$app->homeUrl?>page/view?id=3"><li>Community Guidelines</li></a>
					<!--<a href="<?=\Yii::$app->homeUrl?>site/about"><li>About Us</li></a>-->
					<a href="<?=\Yii::$app->homeUrl?>site/contact"><li>Contact Us</li></a>
				</ul>
			</div>
		</div>
	</footer>
</div>
<!--***** Footer Ends *****-->
<?php $this->endBody() ?>
</body>
<script>
	jQuery(document).ready(function(){
		$('ul.nav li.dropdown').hover(function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
		}, function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
		});

		var hash = window.location.hash;
		hash = hash.replace('#', '');
		console.log(hash);
		if($.trim(hash) != "")
		{
			$("li[data-id="+hash+"]").addClass("active");
		}

		$(".webShareIcons").each(function(){
			var elem = $(this);
			elem.jsSocials({
				showLabel: false,
				showCount: false,
				shares: ["facebook","googleplus", "pinterest", "linkedin", "reddit",
				{
				share: "twitter",           // name of share
				via: "simply_wishes",       // custom twitter sharing param 'via' (optional)
				hashtags: "simplywishes,dream_come_true"   // custom twitter sharing param 'hashtags' (optional)
			}],
			url : elem.attr("data_url"),
			text: elem.attr("data_text"),
		});
		});		
	});
</script>

<script src="<?=\Yii::$app->homeUrl?>assets/js/core/source/AppVendor.js"></script>




</html>
<?php $this->endPage() ?>


