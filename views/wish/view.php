<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Wish */

$this->title = $model->wish_title;
$this->params['breadcrumbs'][] = ['label' => 'Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wish-view">
  <div class="container my-profile">
	<div class="col-md-12 smp-mg-bottom">
	<h3 class="smp-mg-bottom fnt-green "><?=$this->title?></h3>
		<div class="col-md-3 happystory sharefull-list">
			
				<img src="<?=\Yii::$app->homeUrl.$model->primary_image?>"  class="img-responsive" alt="my-profile-Image"><br>
				<p><i class="fa fa-thumbs-o-up fnt-blue"></i><span id="likecmt_<?= $model->w_id ?>"> <?=$model->likesCount?> </span> Likes &nbsp;
				<!--<a class="report-img" title="Report" data-id="<?= $model->w_id ?>"><img  src="<?= Yii::$app->homeUrl ?>images/report.png" alt="">-->
				</a>
				<?php
				  if(!$model->isFaved(\Yii::$app->user->id))
					echo '<span title="Save this wish" data-w_id="'.$model->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-orange"></span>&nbsp;';
				  else
					echo  '<span title="You saved it" data-w_id="'.$model->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-blue"></span>&nbsp;';

				  if(!$model->isLiked(\Yii::$app->user->id))
					echo  '<span title="Like it" data-w_id="'.$model->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-green"></span>';
				  else
					echo  '<span title="You liked it" data-w_id="'.$model->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-pink"></span>';
				?>
				<!--<i class="fa fa-save txt-smp-orange"></i> &nbsp;
				<i class="fa fa-thumbs-o-up txt-smp-green"></i>--> 
				<span  data-placement="top"  data-popover-content=""><img data-placement="top" class="listesinside removelistesinside"  src="<?= Yii::$app->homeUrl ?>images/Share-Icon.png"  /></span>					
				<div class="shareIcons hide" ></div>
				
				</p>
				<!--<div class="shareIcons"></div> -->

			
		</div>
		<div class="col-md-8">
			<p><b>Name : </b><span><a href="<?=Url::to(['account/profile','id'=>$model->wished_by])?>"><span><?=$model->wisherName?></span></a></span></p>
			<p><b>Wish Description : </b><span><?=$model->wish_description?></span></p>
			<p><b>Location of Wish : </b><span><?=$model->location?></span></p>
			<p><b>Date Issued : </b><span><?php echo date("m/d/Y",strtotime($model->expected_date)); ?></span></p>
			<p><b>What Do I Give In Return : </b><span><?=$model->in_return?> </span></p>
			<p><b>Who Can Potentialy Help me : </b><span><?=$model->who_can?> </span></p>
			<p><b>Recipient : </b><span><?=$model->categoryName?></span></p>
			<?php if(!is_null($model->granted_by)){ ?>	
			<p><b>Wish granted on : </b><span><?=$model->granted_date ?></span></p>			
			<p><b>Wish granted by : </b><span><a href="<?=Url::to(['account/profile','id'=>$model->granted_by])?>"><span><?=$model->GrantedWisherName?></span></a></span></p>		
			<?php } ?>			
			<?php if(is_null($model->granted_by) && !\Yii::$app->user->isGuest  && \Yii::$app->user->id!=$model->wished_by){ ?>
			 <?php if($model->non_pay_option == 0 ){ ?>
				<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			   <!-- <form action="https://www.paypal.com/cgi-bin/webscr" method="post"> -->
				  <!-- Identify your business so that you can collect the payments. -->
				  <!--<input type="hidden" name="business" value="dency@abacies.com">-->
				  <input type="hidden" name="business" value="<?=$model->wisher->email?>">
				  <!-- Specify a Buy Now button. -->
				  <input type="hidden" name="cmd" value="_xclick">
				  <input type="hidden" name="return" value="<?=Url::to(['wish/fullfilled','w_id'=>$model->w_id],true)?>">
				  <input type="hidden" name="notify_url" value="<?=Url::to(['wish/verify-granted'],true)?>">
				  <!-- Specify details about the item that buyers will purchase. -->
				  <input type="hidden" name="item_name" value="<?=$model->wish_title?>">
				  <input type="hidden" name="item_number" value="<?=$model->w_id?>">
				  <input type="hidden" name="amount" value="<?=$model->expected_cost?>">
				  <input type="hidden" name="currency_code" value="USD">

				  <!-- Display the payment button. -->
				  <!--<input type="image" name="submit" border="0" 
				  src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_paynow_cc_144x47.png"
				  alt="Buy Now">-->
				  <button class="btn btn-success">Grant This Wish</button>
				  <img alt="" border="0" width="1" height="1"
				  src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
				</form>
			<?php } else if($model->non_pay_option == 1 ){ ?>
					<?php if(!empty($model->process_granted_by)){ ?>
					  <button class="btn btn-success">In Progress</button>					
					<?php } else { ?>
					  <a href="#messagemodal1" data-toggle="modal" ><button class="btn btn-success">Grant This Wish </button></a>
					<?php } ?>
			<?php } else if($model->non_pay_option == 2 ){ ?>
					<?php if(!empty($model->process_granted_by)){ ?>
					<button class="btn btn-success">In Progress</button>					
					<?php } else { ?>
					<a href="#messagemodal2" data-toggle="modal"><button class="btn btn-success">Grant This Wish </button></a>
					<?php } ?>
			<?php } ?>
			<?php } else if(!is_null($model->granted_by)&& \Yii::$app->user->isGuest){ ?>
				<a href="<?=Url::to(['site/login','red_url'=>Yii::$app->homeUrl.'wish/view?id='.$model->w_id])?>"><button class="btn btn-success">Grant This Wish </button></a>
			<?php } else if(\Yii::$app->user->isGuest) { ?>
				<a href="<?=Url::to(['site/login','red_url'=>Yii::$app->homeUrl.'wish/view?id='.$model->w_id])?>"><button class="btn btn-success">Grant This Wish </button></a>
			<?php } ?>
			
			<?php if((is_null($model->granted_by)) && (!\Yii::$app->user->isGuest) && (\Yii::$app->user->id==$model->wished_by) && (!empty($model->process_status)))
				{
					$date1 = new DateTime($model->process_granted_date);
					$date2 = new DateTime(date("Y-m-d"));				
					$diff = $date2->diff($date1)->format("%a");					
					if($diff > 30)
					{
						echo '<button class="btn btn-info" id="grant_progress_wishes" >Fulfilled </button>';
						
						echo '<button class="btn btn-danger"  style="margin-left:15px" id="Resubmit_wishes"> Re-submit </button>';
					} else {					
						echo '<button class="btn btn-info">In Progress</button>';
					}		
				} 
				else if((is_null($model->granted_by)) && (!\Yii::$app->user->isGuest) && (\Yii::$app->user->id==$model->wished_by))
				{
				 echo '<a href="'.Url::to(['wish/update','id'=>$model->w_id]).'"><button class="btn btn-info">Update Wish</button></a>';
				}
			?>
			
		</div>
	</div>
</div>
</div>




<!-- modal NON financial Starts -->
	<div class="modal fade" id="messagemodal1" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Non-financial</h4>
		  </div>
		  <div class="modal-body">
			<div class="media">
			  <div class="media-left list-icon">

			  </div>
			  <div class="media-body">
				<h4 class="media-heading">“ Wisher would like to receive this wish via ”  
				</h4>
				<br>
				<?php if($model->show_mail_status == 1){ ?>
					<p>Mail to this address : <b><?=$model->show_mail?></b> </p>
				<?php } ?>	
				<?php if(($model->show_mail_status == 1) && ($model->show_person_status == 1)){ ?>
				    <p>or </p>
				<?php } ?>	
				<?php if($model->show_person_status == 1){ ?>
				    <p>In Person at this location :  <b><?=$model->show_person_location?> on <?=$model->show_person_date ?></b></p>
				<?php } ?>		
				<?php if((($model->show_mail_status == 1) || ($model->show_person_status == 1)) && ($model->show_reserved_status == 1)){ ?>
					<p>or </p>
				<?php } ?>	
				<?php if($model->show_reserved_status == 1){ ?>
					<p>Reserved at this location : <b><?=$model->show_reserved_name?> , <?=$model->show_reserved_location?> on <?=$model->show_reserved_date ?></b></p>
				<?php } ?>	
				<?php if((($model->show_mail_status == 1) || ($model->show_person_status == 1) || ($model->show_reserved_status == 1) )&& ($model->show_other_status == 1)){ ?>
					<p>or </p>
				<?php } ?>	
				<?php if($model->show_other_status == 1){ ?>
					<p>Other : <b><?=$model->show_other_specify ?></b></p>
				<?php } ?>	

			  </div>
			</div>
			</br>
			<div class="form-group">
			
			<input type="checkbox" class="msg-check" name="i_agree_non_fulfilled" id="i_agree_non_fulfilled" value="1" > I agree to fulfill this wish in the manner specified by the wisher and within one month of the date that I accept it as a grantor. In the meanwhile, this wish will be marked as "In Progress" and after one month, it will be marked as "Fulfilled". The Wisher should update or ressubmit their wish if it has not been fulfilled after one month. </input>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="send-msg-check btn btn-primary">Submit</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		  </div>
		</div>
	  </div>
	</div>
	<!-- modal Ends -->
	
	
	
	
<!-- modal DEcider Starts -->
	<div class="modal fade" id="messagemodal2" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Decide Later</h4>
		  </div>
		  <div class="modal-body">
			<div class="media">
			  <div class="media-left list-icon">
					
			  </div>
			  <div class="media-body">
				<h4 class="media-heading">“ Wisher has not specified a delivery method for this wish. Please message the wisher to arrange wish delivery ”</h4>
			  </div>
			</div>
			</br>
			<div class="form-group">
			<label for="message">Enter Your Message</label>
			<textarea class="msg form-control" name="msg-textarea" id="msg-textarea" rows="4"></textarea>
			</div>
			<div class="form-group">
			
			<input type="checkbox" class="msg" name="i_agree_decide" id="i_agree_decide" value="1" > I agree to fulfill this wish in the manner specified by the wisher and within one month of the date that I accept it as a grantor. In the meanwhile, this wish will be marked as "In Progress" and after one month, it will be marked as "Fulfilled". The Wisher should update or ressubmit their wish if it has not been fulfilled after one month. </input>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="send-msg-check-decide btn btn-primary">Submit</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		  </div>
		</div>
	  </div>
	</div>
	<!-- modal Ends -->
	
	
	
<script>
	$(".shareIcons").jsSocials({
		showLabel: false,
		showCount: false,
		shares: ["facebook", "googleplus", "pinterest", "linkedin" ,"twitter","reddit" ]
	});
	$(document).on('click', '.like-wish, .fav-wish', function(){ 
	//$(".like-wish, .fav-wish").on("click",function(){
		var wish_id = $(this).attr("data-w_id");
		var type = $(this).attr("data-a_type");
		var elem = $(this);
		$.ajax({
			url : '<?=Url::to(['wish/like'])?>',
			type: 'GET',
			data: {w_id:wish_id,type:type},
			success:function(data){
				if(data == "added"){
					if(type=="fav"){
						elem.removeClass("txt-smp-orange");
						elem.addClass("txt-smp-blue");
					}
					if(type=="like"){
						elem.removeClass("txt-smp-green");
						elem.addClass("txt-smp-pink");
						var likecmt = $("#likecmt_"+wish_id).text();
						likecmt = parseInt(likecmt) + parseInt(1);
						$("#likecmt_"+wish_id).text(likecmt);
					}
				}
				if(data == "removed"){
					if(type=="fav"){
						elem.addClass("txt-smp-orange");
						elem.removeClass("txt-smp-blue");
					}
					if(type=="like"){
						elem.addClass("txt-smp-green");
						elem.removeClass("txt-smp-pink");
						var likecmt = $("#likecmt_"+wish_id).text();
						likecmt = parseInt(likecmt) - parseInt(1);
						$("#likecmt_"+wish_id).text(likecmt);
					}
				}

				console.log(data);
			}
		});
	});
	
	$(document).on('click', '.deletecheck', function(){ 
		if(confirm("Are Sure To Delete this Wish ?"))
		{
			$.ajax({
			url : '<?=Url::to(['wish/ajax-delete'])?>',
			type: 'POST',
			data: {id:<?= $model->w_id ?>},
			success:function(data){				
						window.location.href="<?= Url::to(['account/my-account'],true); ?>"; 
				}	
			});
		
		}
		else{
			return false;
		}
	});
</script>


	<script>	
	var isVisible = false;
var clickedAway = false;

$(document).on("click", ".listesinside", function() {
	$(function(){
		$('.listesinside').popover({   
			html: true,
			content: function () {
				var clone = $($(this).parents(".sharefull-list").find(".shareIcons")).clone(true).removeClass('hide');
				return clone;
			}
		}).click(function(e) {
			e.preventDefault();
			clickedAway = false;
			isVisible = true;
		});
	});
 
});

$(document).click(function (e) {
    if (isVisible & clickedAway) {
        $('.listesinside').popover('hide');
        isVisible = clickedAway = false;
    } else {
        clickedAway = true;
    }
});

$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState.click = false;
});


/* $(document).on('click','.jssocials-shares',function(){
		 $('.listesinside').popover('hide');
	}); */
	
/* $(document).on("click", ".report-img", function() {
	var wish_id = $(this).attr("data-id");
	 if($.trim(wish_id) !== "" )
	 {
		$.ajax({
			url : '<?=Url::to(['wish/report'])?>',
			type: 'GET',
			data: {w_id:wish_id},
			success:function(data){
				if($.trim(data) == "added")
				{
					alert(" Thanks For your Report. ");
				}
				console.log(data);
			}
		});
	 }
}); */


	$(".send-msg-check").on("click",function(){

			var processstatus = 0;

			 if($('#i_agree_non_fulfilled').prop("checked") == true){
				 var processstatus = 1;		
			
			 }
			 else if($('#i_agree_non_fulfilled').prop("checked") == false){
				alert("Please Accept The Agreement");
				return false;
			 }
			 
			 var wish_id = "<?=$model->w_id?>";
			var send_to = "<?=$model->wished_by?>";
			var send_from = "<?=\Yii::$app->user->id?>";
			
			 var send_message = 1;
			 $.ajax({
				url : '<?=Url::to(['wish/process-wish'])?>',
				type : 'POST',
				data : {processstatus:processstatus,wish_id:wish_id,send_message:send_message},
				success: function(response){
					$.ajax({
						url : '<?=Url::to(['account/send-message-wishes-contact-details'])?>',
						type : 'POST',
						data : {send_from:send_from,send_to:send_to,wish_id:wish_id },
						success: function(response){
							$('#messagemodal1').modal('hide');
							alert("This Wish Has Been Granted Successfully.");
							location.reload();
						}
					});
					
				}
			});
			
			
		});
		
	$(".send-msg-check-decide").on("click",function(){
			var processstatus = 0;
			
			 if($('#i_agree_decide').prop("checked") == true){
				 var processstatus = 1;					
			 }
			 else if($('#i_agree_decide').prop("checked") == false){
				alert("Please Accept The Agreement");
				return false;
			 }
			var wish_id = "<?=$model->w_id?>";
			var msg = $('#msg-textarea').val();
			var send_to = "<?=$model->wished_by?>";
			var send_from = "<?=\Yii::$app->user->id?>";
			var send_message = 0;
			if($.trim(msg) === "")
			{
				alert("Please check the message.");
				return false;				
			}
			
			$.ajax({
				url : '<?=Url::to(['wish/process-wish'])?>',
				type : 'POST',
				data : {processstatus:processstatus,wish_id:wish_id,send_message:send_message},
				success: function(response){
					$.ajax({
						url : '<?=Url::to(['account/send-message-wishes'])?>',
						type : 'POST',
						data : {msg:msg,send_from:send_from,send_to:send_to},
						success: function(response){
							alert("This Wish Has Been Granted Successfully.");
							$('#messagemodal2').modal('hide');
							location.reload();
						}
					});
				}
			});
			
			
			
		});	
	
	
		
	$("#Resubmit_wishes").on("click",function(){
			
			var wish_id = "<?=$model->w_id?>";
			var userid  = "<?=\Yii::$app->user->id?>";
			
			if (confirm('Are You Sure to Re-Submit this wish again.')) {
					$.ajax({
						url : '<?=Url::to(['wish/resubmit-process-wish'])?>',
						type : 'POST',
						data : { wish_id:wish_id,userid:userid},
						success: function(response){
							alert("This Wish Has Been Re-Submitted Successfully.");
							location.reload();
						}
					});
				} 
			
		});	
	
	
		$("#grant_progress_wishes").on("click",function(){
			
			var wish_id = "<?=$model->w_id?>";
			var userid  = "<?=\Yii::$app->user->id?>";
			
			if (confirm('Are You Sure Your Wish Has been Fullfield.')) {
					 $.ajax({
						url : '<?=Url::to(['wish/grant-process-wish'])?>',
						type : 'POST',
						data : { wish_id:wish_id,userid:userid},
						success: function(response){
							alert("This Wish Has Been Fulfilled Successfully.");
							location.reload();	
						}
					}); 
				} 
			
		});
		
	
</script>

