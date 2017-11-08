<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserProfile;
use app\models\EditorialComments;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Editorials';
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-create">

    <h1 class="fnt-green" >Editorial</h1>
    <?php if (Yii::$app->session->hasFlash('error_comments')): ?>

        <div class="alert alert-success">
            Oops!Something went wrong. Plesae try again later.
        </div>
	<?php endif; ?>	
    <?php if(Yii::$app->session->hasFlash('login_to_comment')): ?>

        <div class="alert alert-success">
            Please login to post a comment!
        </div>

    <?php endif; ?>		
	<?php
	
		if(isset($model) && !empty($model))
		{		
			$profile = UserProfile::find()->where(['user_id'=>$model->created_by])->one();		
				?>
			<!---<div class="row">		
				<div class="form-group col-md-2">
					<img src="<?=Yii::$app->homeUrl?><?= $model->e_image; ?>" height="100px"/>				
				</div>
				<div class="form-group col-md-8">
				<h4><?= $model->e_title; ?></h4>
				<p><?= $model->e_text ?></p>				
				</div>
			</div>-->
			
			
			<div class="row edit">
				<div class="form-group col-md-8">
					<p><?= $model->e_title; ?></p>
					<p><img src="<?=Yii::$app->homeUrl?><?php echo $profile->profile_image; ?>" height="100px"/></a> <a class="atagcolor" href="<?=Yii::$app->homeUrl?>account/profile?id=<?php echo $model->created_by ?>" >&nbsp;Author: &nbsp;<?php echo $profile->Fullname; ?></a></p>
					<p>Date: &nbsp;<?php echo date("m/d/Y",strtotime($model->created_at)); ?></p>
					<p><?= $model->e_text ?></p>					
				</div>
				<div class="form-group col-md-4">				
				
					<div class="shareIcons" data_text="<?php echo $model->e_title; ?>" data_url="<?= Url::to(['editorial/editorial-page','id'=>$model->e_id],true) ?>" ></div>
					<div class="editrightimg">
					<center><img src="<?=Yii::$app->homeUrl?><?php echo $model->e_image; ?>" height="180px"/></center>
					</div>
					<br>
					<div><?php if($model->featured_video_url != ''){ 
							$url = $model->featured_video_url; 
										
					if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
							echo "<iframe width='600' height='300' src=".$url." controls></iframe>";
						} else {
							echo $url;
					}
					}
			
					?>
					</div>
				
				</div>
				
				
			</div>
			
			<div class="row">
			<h3 class="left fnt-green" >Comments:</h3>
				
			<?php $form = ActiveForm::begin(['action' =>['editorial/editorial-comments']]); ?>
				 
					 <?= $form->field($listcomments, 'comments')->textarea(['rows' => 6])->label(false) ?>			
					 <?= $form->field($listcomments, 'e_id')->hiddeninput(['value'=>$model->e_id])->label(false) ?>			
						
					<div class="form-group">
						<?= Html::submitButton('Post', ['class' =>'btn btn-primary']) ?>
					</div>
					
				<?php ActiveForm::end(); ?>
					
			</div>
			
			<?php if(isset($comments) && !empty($comments)){ 
					foreach($comments as $user)
					{
						$profile = UserProfile::find()->where(['user_id'=>$user->user_id])->one();						
				?>
				  <div class="row">		
						<div class="form-group col-md-2">
							<img src="<?=Yii::$app->homeUrl?><?= $profile->profile_image; ?>" height="100px"/>				
						</div>
						<div class="form-group col-md-8">	
							<h4><a class="atagcolor" href="<?=Yii::$app->homeUrl?>account/profile?id=<?php echo $profile->user_id ?>" ><?= $profile->firstname.' '.$profile->lastname ?></a></h4>						
							<p><?= $user->comments ?></p>
				<span class="on-reply" style="cursor: pointer;" for="<?= $user->e_comment_id ?>" ><b><u>Reply<u></b></span>
				
				<div  style="display:none; margin-top:10px" id="<?php echo "replylist_".$user->e_comment_id ?>" class="comment-form2 reply full" data-plugin="comment-reply">	
					<!--<a class="close" data-action="comment-close">X</a> -->
					<?php $form = ActiveForm::begin(['action' =>['editorial/commentreply']]); ?>				 
					 <?= $form->field($listcomments, 'comments')->textarea(['rows' => 3])->label(false) ?>			
					 <?= $form->field($listcomments, 'e_id')->hiddeninput(['value'=>$model->e_id])->label(false) ?>			
					 <?= $form->field($listcomments, 'parent_id')->hiddeninput(['value'=>$user->e_comment_id])->label(false) ?>
					 <div class="form-group">
						<?= Html::submitButton('Reply-Post', ['class' =>'btn btn-primary']) ?>
					</div>					
					<?php ActiveForm::end(); ?>
				</div>
				
						<?php 
							$replycomments = EditorialComments::find()->where(['parent_id'=>$user->e_comment_id])->orderBy('e_comment_id Desc')->all();
							
							if($replycomments)
							{
								foreach($replycomments as $replyuser)
								{
									$replyprofile = UserProfile::find()->where(['user_id'=>$replyuser->user_id])->one();	
									?>
						<div class="row">		
							<div class="form-group col-md-1">
							<img src="<?=Yii::$app->homeUrl?><?= $replyprofile->profile_image; ?>" height="50px"/>				
						</div>
						<div class="form-group col-md-10">	
							<h5><a class="atagcolor" href="<?=Yii::$app->homeUrl?>account/profile?id=<?php echo $replyprofile->user_id ?>" ><?= $replyprofile->firstname.' '.$replyprofile->lastname; ?></a></h5>						
							<p><?= $replyuser->comments ?></p>
						</div>	
						</div>	
									<?php
								}
							}		
						?>
				
				
						</div>
				  </div>
			
				<?php } } ?>
				
			<?php			
		}
	?>
	
</div>


<script>
$(document).ready(function(){
    $(".on-reply").click(function(){ 
		var id = $(this).attr("for");
		
		if($("#replylist_"+id).is(':hidden'))
		{
			$("#replylist_"+id).show();			
		} else {
			$("#replylist_"+id).hide();	
		}  	
		
    });
	/* $(".close").click(function(){ 
		$(this).parent().hide();
    }); */
});
</script>
<script>
	$(".shareIcons").each(function(){
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
</script>
<style>
iframe{
	max-width: 100%;
}
</style>

