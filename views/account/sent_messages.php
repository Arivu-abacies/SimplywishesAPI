<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
?>	

	<?php echo $this->render('_profilenew',['user'=>$user,'profile'=>$profile])?>
	<div class="col-md-8">
		<ul class="nav nav-tabs smp-mg-bottom" role="tablist">
		  <li role="presentation" >
			<a href="<?=\Yii::$app->homeUrl?>account/inbox-message"  role="tab">Inbox</a>
		  </li>
		  <li role="presentation" class="active" >
			<a>Sent Mail</a>
		  </li>
		</ul>
	   <div class="tab-content" >
		<div role="tabpanel" class="tab-pane " id="inboxmailtab">
		
		</div>
		<div role="tabpanel" class="tab-pane active grid" id="sentmailtab">		
		<div class="message">
		<ul class="list-group">
			<li class="list-group-item">
				<input type="checkbox" id="select_all" style="margin-right:10px"  ></input>	
				<a href="#messagemodalOne" id="sendmessage" data-toggle="modal"><button class="btn btn-warning">Send Message</button></a>
				<button class="btn btn-danger pull-right" id="multi_delete" >Multi Delete</button>
			</li>
		<?php 	
			$current_user = \app\models\Userprofile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
			
			foreach($messages as $key=>$msg){
				$profile = \app\models\Userprofile::find()->where(['user_id'=>$msg['recipient_id']])->one();
				
				$reply="";
				if(isset($msg['threads']) && !empty($msg['threads']))
				{
					$reply =" , me";	
					$profile = \app\models\Userprofile::find()->where(['user_id'=>$msg['recipient_id']])->one();					
				}else{
					$profile = \app\models\Userprofile::find()->where(['user_id'=>$msg['sender_id']])->one();
				}
				
				
			 	echo '<li class="list-group-item" id="li_list_'.$msg['m_id'].'" >		
						<input type="checkbox" class="checkBoxClass" name="selection[]" value="'.$msg['m_id'].'" ></input>					
						 <span style="cursor:pointer" class="pull-right remove_delete" title="Remove"  for="'.$msg['m_id'].'"><i class="fa fa-trash-o" aria-hidden="true"> </i></span>
						 
						 <div class="list-icon">
							To:  <a href="'.Url::to(['profile','id'=>$profile['user_id']]).'" target="_blank" ><img  src="'.\Yii::$app->homeUrl.$profile['profile_image'].'" alt=""></a>
						</div>
						
					<div class="smp_expand pointerclass" data-toggle="collapse" title="Click here To View Conversation">
											
						<div class="list-group-item-heading">'.$profile['fullname'].$reply.'</div>
						<p class="list-group-item-text">
						
						<span class="label label-primary pull-right">Date:'.$msg['created_at'].'</span></p>
					</div>
					<ul class="collapse detail">';					
					
						echo '<li class="media">						  
						  <div class="media-body">						
							<p class="list-group-item-text">'.$msg['text'].'</p>							
						  </div>
						</li>';
						
						echo '<li class="media media_textbox">
							<div class="form-group">
								<label for="message">Enter Your Message</label>
								<textarea id="'.$key.'_msg" class="form-control" rows="2"></textarea>
							</div>
							<button type="button" id="rpy_'.$msg['m_id'].'" data-send_to="'.$msg['recipient_id'].'" data-msg_id ="'.$msg['m_id'].'" class="send-msg btn btn-primary pull-right">Reply</button>
						</li>
						';

				if(isset($msg['threads']) && !empty($msg['threads']))
					{						
						arsort($msg['threads']);
						foreach($msg['threads'] as $key2=>$thread){
							$profile = \app\models\Userprofile::find()->where(['user_id'=>$thread['send_by']])->one();
							echo '<li class="media">
							  <div class="media-left list-icon">
								 <img src="'.\Yii::$app->homeUrl.$profile->profile_image.'" alt="">
							  </div>
							  <div class="media-body">
								<h4 class="media-heading">'.$profile->fullname.'</h4>
								<p class="list-group-item-text">'.$thread['text'].'<span class="label label-primary pull-right">Date:'.$thread['created_at'].'</span></p>
								
							  </div>
							</li>';
						} 	 
					}						
				
					echo '</ul>
					</li>'; 
			}
		?>

		</ul>
	 </div>
	 
		</div>
	</div>
   </div>
</div>
	
	
	<!-- modal Starts -->
	<div class="modal fade" id="messagemodalOne"  tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	  <form id='project-form'>
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Message</h4>
		  </div>
		  <div class="modal-body">
			<div class="media">
		
			  <?php 
			 $data =  \app\models\Userprofile::find()->select(['CONCAT(firstname," ",lastname) as value', 'CONCAT(firstname," ",lastname) as  label','user_id as id'])->where(['!=','user_id',\Yii::$app->user->id])->asArray()->all();	
				
					echo AutoComplete::widget([
						'name' => 'adduser',
						'id' => 'adduser',
						'options' => ['class' => 'form-control','placeholder'=>'Search Name'],						
						'clientOptions' => [
						 'appendTo'=>'#project-form',
							'source' => $data, 
							'autoFill'=>true,
							 'select' => new JsExpression("function( event, ui ) {
									$('#senduserid').val(ui.item.id);			
										}"),
								],
							]); 
?>
				
<input type="hidden" name="senduserid" id="senduserid" />
			</div>
			</br>
			<div class="form-group">
			<label for="message">Enter Your Message</label>
			<textarea id="msgOne" class="form-control" rows="4"></textarea>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="send-msgOne btn btn-primary">Send</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
		  </form>
	  </div>
	</div>
	<!-- modal Ends -->
	<script>
	$("#sendmessage").on("click",function(){
		$("#addusers").val("");
	});
	
	$("#select_all").click(function () {
			$(".checkBoxClass").prop('checked', $(this).prop('checked'));
		});
		
		$(".send-msgOne").on("click",function(){
			console.log($('#msgOne').val());
			var msg = $('#msgOne').val();
			if($.trim(msg) === "")
			{
				alert("Please check the message.");
				return false;
				
			}
			var send_to = $("#senduserid").val();
			var send_from = "<?=\Yii::$app->user->id?>";
			$.ajax({
				url : '<?=Url::to(['account/send-message-inbox'])?>',
				type : 'POST',
				data : {msg:msg,send_from:send_from,send_to:send_to},
				success: function(response){
					location.reload();
				}
			});
		});
	
	
		$("#multi_delete").click(function(){				 
		    var r = confirm("Are you Sure To Delete!");
		    if (r == true) {
				var msg_id = $.map($('input[name="selection[]"]:checked'), function(c){return c.value; })
				if($.trim(msg_id) === "")
				 {
					alert("Please Select the Checkbox to Delete.");
					return false;
				  }				
				 $.ajax({
				   url: '<?=Yii::$app->homeUrl."account/multi-delete-send-message"?>',
				   type: 'POST',
				   data: {  msg_id: msg_id,
				   },
				   success: function(data) {		
						location.reload();
				   }
				 }); 
				}		
			 });
		$(".remove_delete").click(function(){	
		var r = confirm("Are you Sure To Delete!");
		   if (r == true) {
			 var msg_id = $(this).attr("for");
			 
			 $.ajax({
				   url: '<?=Yii::$app->homeUrl."account/delete-send-message"?>',
				   type: 'POST',
				   data: {  msg_id: msg_id,
				   },
				   success: function(data) {		
						$("#li_list_"+msg_id).hide();
				   }
				 }); 
		   }	 
		});		
	</script>
	
	<script>
	//jQuery(document).ready(function(){
	$('ul.nav li.dropdown').hover(function() {
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
			}, function() {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
		});
		$(".shareIcons").jsSocials({
			showLabel: false,
			showCount: false,
			shares: ["facebook", "twitter", "googleplus", "pinterest", "linkedin"]
		});
		$(".smp_expand").on( "click", function() {
			$(this).next().slideToggle(200);
			//$(this).parent().siblings().children().next().slideUp(); Arivazahgan test
			$('li').removeClass('active');
			$(this).parent('li').addClass('active'); 
		});

		$(".send-msg").on("click",function(){	

			var id = $(this).attr('id');

			var send_to = $(this).attr('data-send_to');
			var msg_id = $(this).attr('data-msg_id');
			var msg = $('#'+msg_id+'_msg').val();
			if($.trim(msg) === "")
			{
				alert("Please check the message.");
				return false;
				
			}
		
			var prof_image = "<?=\Yii::$app->homeUrl.$current_user->profile_image?>";
			var fullname = "<?=$current_user->fullname?>";
			var elem = $(this);
			console.log(msg);
			var send_from = "<?=\Yii::$app->user->id?>";
			
			$("#"+id).attr('disabled','disabled');
			
			
			
			$.ajax({
				url : '<?=Url::to(['account/reply-message'])?>',
				type : 'POST',
				data : { msg:msg, send_from:send_from, send_to:send_to, msg_id:msg_id },
				success: function(response){
					var data = $.parseJSON(response);
					//console.log(response.status);
					if(data.status){
						$('#'+msg_id+'_msg').val("");
						var html = '<li class="media"><div class="media-left list-icon"><img  src="'+prof_image+'" alt=""></div><div class="media-body"><h4 class="media-heading">'+fullname+'</h4><p class="list-group-item-text">'+msg+'<span class="label label-primary pull-right">Date:Now</span></p></div></li>';
					//$(elem).parent('li').append(html);
						$( html ).insertAfter( $(elem).parent('li'));
						$("#"+id).removeAttr('disabled');
					}
					
					
				},
				 error: function (response) {
						$("#"+id).removeAttr('disabled');
					}
			});					
		

		});
	

	//});
	</script>
<style>
input.checkBoxClass {
    float: left;
    margin-top: 15px;
    margin-right: 8px;
}
.list-icon{
    float: left;
    margin-right: 10px;
}
.list-group-item-heading {
    padding-top: 10px;
}
.list-icon a img {
    max-width: 40px;
	max-height:40px;
    border-radius: 100%;
}
.pointerclass{
	cursor: pointer;
}
</style>
	
