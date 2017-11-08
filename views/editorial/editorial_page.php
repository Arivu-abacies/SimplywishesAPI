<?php

use yii\helpers\Html;
use app\models\UserProfile;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Editorials';
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-create">

    <h1 class="fnt-green" >Editorial</h1>
	
	<?php
	
		if(isset($model) && !empty($model))
		{
			foreach($model as $tmp)
			{
				
				$profile = UserProfile::find()->where(['user_id'=>$tmp->created_by])->one();	
				
				?>
			<!--<div class="row happystory">
				<div class="form-group col-md-8">
				<a href="<?=Yii::$app->homeUrl?>editorial/editorial-page?id=<?php echo $tmp->e_id; ?>"><?php echo $tmp->e_title; ?></a>
				<p><?php echo substr($tmp->e_text,0,250)?>..!</p>
				<a href="<?=Yii::$app->homeUrl?>editorial/editorial-page?id=<?php echo $tmp->e_id; ?>">Read More...!</a>
				</div>
				
				<div class="form-group col-md-2">
					<a href="<?=Yii::$app->homeUrl?>editorial/editorial-page?id=<?php echo $tmp->e_id; ?>">
					<img src="<?=Yii::$app->homeUrl?><?php echo $tmp->e_image; ?>" height="100px"/></a>				
				</div>
				
			</div>-->
			
			<div class="row edit">
				<div class="form-group col-md-9">
					<p><?php echo $tmp->e_title; ?></p>
					<p><img src="<?=Yii::$app->homeUrl?><?php echo $profile->profile_image; ?>" height="100px"/> <a class="atagcolor" href="<?=Yii::$app->homeUrl?>account/profile?id=<?php echo $tmp->created_by ?>" >&nbsp;Author: &nbsp;<?php echo $profile->Fullname ?></a></p>
					<p>Date: &nbsp;<?php echo date("m/d/Y",strtotime($tmp->created_at)); ?></p>					
					<p><?php echo substr($tmp->e_text,0,450)?></p>
					<a href="<?=Yii::$app->homeUrl?>editorial/editorial-page?id=<?php echo $tmp->e_id; ?>"><h5>Read More</h5></a>
				</div>
		
				<div class="form-group col-md-3">				
					
					<div class="shareIcons" data_text="<?php echo $tmp->e_title; ?>" data_url="<?= Url::to(['editorial/editorial-page','id'=>$tmp->e_id],true) ?>"> </div>
					<div class="editrightimg">
						<center><a href="<?=Yii::$app->homeUrl?>editorial/editorial-page?id=<?php echo $tmp->e_id; ?>"><img class="imagesize" src="<?=Yii::$app->homeUrl?><?php echo $tmp->e_image; ?>" /></a></center>	
					</div>					
				</div>
				
			</div>
			
			<?php
			}
		}
	?>
	
</div>
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

