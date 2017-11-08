<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

?>
 <?php if (Yii::$app->session->hasFlash('delactiveAllWishes')){ ?>

        <div class="alert alert-success">
            Wishes Details and Happy stories Is Removed From The Site.
        </div>

	<?php } else if(Yii::$app->session->hasFlash('delactiveAllUsers')) {	 ?>
	<div class="alert alert-success">
           All Users Details Has Beened Removed From The Site.
        </div>
 <?php } ?>	
 
<div class="row page-header">
  <div class="container my-profile">
	<div class="col-md-12">
		 <h2 class="fnt-green"><i class="fa fa-cogs fa-2x" style="color: #006699;
padding-right: 10px;" ></i>Setting</h3>
		
	</div>
	<div class="col-md-12 smp-mg-bottom"></div>
	<div class="col-md-12 link-thumb-contain">		
		<div class="col-md-2">
			<i class="fa fa-users fa-5x fnt-red" aria-hidden="true"></i>			
		</div>
		<div class="col-md-8">
		<p style="text-align: left;">Reset Option for the Remove Or Delete All The User except the Admin Removed the Details From the Simplywish Site </p>
		</div>
		<div class="col-md-2">
		<?php  echo Html::beginForm(['/site/reset-all-user'], 'post') 
						. Html::submitButton(
        											'<i class="fa fa-refresh"></i>  Reset User',
        											['class' => 'btn btn-danger',
													'data-confirm' => Yii::t('yii', 'Are You Sure To Remove All The Users Details From The Website?'),
													]
        											)
        			. Html::endForm();  ?>
		
		</div>
	</div>
	<div class="col-md-12 smp-mg-bottom"></div>
	<div class="col-md-12 link-thumb-contain">		
		<div class="col-md-2">
			<i class="fa fa-tasks fa-5x fnt-red" aria-hidden="true"></i>			
		</div>
		<div class="col-md-8">
		<p style="text-align: left;">Reset Option for the Remove Or Delete All The Wishes Details and Happy Stories Details From the Simplywish Site </p>
		</div>
		<div class="col-md-2">
		<?php  echo Html::beginForm(['/site/reset-all-user-wishes'], 'post') 
						. Html::submitButton(
        											'<i class="fa fa-refresh"></i>  Reset Wishes',
        											['class' => 'btn btn-danger',
													 
													  'data-confirm' => Yii::t('yii', 'Are You Sure To Remove All The Wishes Details From The Website?'), 
													  		
													 ]
        									)
						. Html::endForm();  ?>
					
		</div>
	</div>
	<div class="col-md-12 smp-mg-bottom"></div>

  </div>
</div>

<style>
.fnt-red{
	color:#a94442;
}
</style>

