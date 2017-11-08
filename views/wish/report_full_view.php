<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Wish */

$this->title = "Report Action View : ".$model->wish_title;
$this->params['breadcrumbs'][] = ['label' => 'Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wish-view">
  <div class="container my-profile">
	<div class="col-md-12 smp-mg-bottom">
	<h3 class="smp-mg-bottom fnt-green "><?=$this->title?></h3>
		<div class="col-md-3 happystory sharefull-list">			
				<img src="<?=\Yii::$app->homeUrl.$model->primary_image?>"  class="img-responsive" alt="my-profile-Image"><br>
				<p><i class="fa fa-thumbs-o-up fnt-blue"></i> <?=$model->likesCount?> Likes &nbsp;				
		</div>
		<div class="col-md-8">
		
			<p><b>Name : </b><span><a href="<?=Url::to(['account/profile','id'=>$model->wished_by])?>"><span><?=$model->wisherName?></span></a></span></p>
			<p><b>Wish Description : </b><span><?=$model->wish_description?></span></p>
			<p><b>Iam Located In : </b><span><?=$model->location?></span></p>
			<p><b>Expected Date : </b><span><?=$model->expected_date?></span></p>
			<p><b>What Do I Give In Return : </b><span><?=$model->in_return?> </span></p>
			<p><b>Who Can Potentialy Help me : </b><span><?=$model->who_can?> </span></p>
			<p><b>Recipient : </b><span><?=$model->categoryName?></span></p>
			<?php if(!is_null($model->granted_by)){ ?>	
			<p><b>Wish granted on : </b><span><?=$model->granted_date ?></span></p>			
			<p><b>Wish granted by : </b><span><a href="<?=Url::to(['account/profile','id'=>$model->granted_by])?>"><span><?=$model->GrantedWisherName?></span></a></span></p>		
			<?php } ?>			
			
			<button class="btn btn-danger deletecheck">Delete </button>
			
		</div>
	</div>
</div>
</div>
<script>
	$(document).on('click', '.deletecheck', function(){ 
		if(confirm("Are Sure To Delete this Wish ?"))
		{
			$.ajax({
			url : '<?=Url::to(['wish/report-delete'])?>',
			type: 'POST',
			data: {id:<?= $model->w_id ?>},
			success:function(data){				
						window.location.href="<?= Url::to(['wish/report-action'],true); ?>"; 
				}	
			});
		
		}
		else{
			return false;
		}
	});
</script>


