<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
?>	
<?php 
/* 
  $data =  \app\models\Userprofile::find()->select(['CONCAT(firstname," ",lastname) as value', 'CONCAT(firstname," ",lastname) as  label','user_id as id'])->where(['!=','user_id',\Yii::$app->user->id])->asArray()->all();
 
echo "<pre>";
print_r($data);
exit;
  */
?>
	<?php echo $this->render('_profilenew',['user'=>$user,'profile'=>$profile])?>
	<h3 class="smp-mg-bottom fnt-green">Inbox</h3>
	<div class="message">
		<ul class="list-group">
			<li class="list-group-item">
				<a href="#messagemodalOne" id="sendmessage" data-toggle="modal"><button class="btn btn-warning">Send Message</button></a>
			</li>
		<?php 	
			$current_user = \app\models\Userprofile::find()->where(['user_id'=>\Yii::$app->user->id])->one();
			foreach($messages as $key=>$msg){
				$profile = \app\models\Userprofile::find()->where(['user_id'=>$key])->one();
				echo '<li class="list-group-item">
					<a class="smp_expand" data-toggle="collapse" title="Click here To View Conversation">
						<div class="list-icon">
							<img src="'.\Yii::$app->homeUrl.$profile->profile_image.'" alt="">
						</div>
						<div class="list-group-item-heading">'.$profile->fullname.'</div>
						<p class="list-group-item-text">
						<span class="label label-primary pull-right">Date:'.$msg['created_at'].'</span></p>
					</a>
					<ul class="collapse detail">';
						echo '<li class="media media_textbox">
							<div class="form-group">
								<label for="message">Enter Your Message</label>
								<textarea id="'.$key.'_msg" class="form-control" rows="2"></textarea>
							</div>
						</li>
						<li class="media media_button"><button type="button" data-send_to="'.$key.'" class="send-msg btn btn-primary ">Reply</button></li>';
						arsort($msg['threads']);
					foreach($msg['threads'] as $thread){
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
					echo '</ul>
					</li>';
			}
		?>

		</ul>
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
	
		$(".send-msgOne").on("click",function(){
			console.log($('#msgOne').val());
			var msg = $('#msgOne').val();
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
			$(this).parent().siblings().children().next().slideUp();
			$('li').removeClass('active');
			$(this).parent('li').addClass('active');
		});

		$(".send-msg").on("click",function(){			
			var send_to = $(this).attr('data-send_to');
			var msg = $('#'+send_to+'_msg').val();
			var prof_image = "<?=\Yii::$app->homeUrl.$current_user->profile_image?>";
			var fullname = "<?=$current_user->fullname?>";
			var elem = $(this);
			console.log(msg);
			var send_from = "<?=\Yii::$app->user->id?>";
			$.ajax({
				url : '<?=Url::to(['account/reply-message'])?>',
				type : 'POST',
				data : {msg:msg,send_from:send_from,send_to:send_to},
				success: function(response){
					var data = $.parseJSON(response);
					//console.log(response.status);
					if(data.status){
						$('#'+send_to+'_msg').val("");
						var html = '<li class="media"><div class="media-left list-icon"><img src="'+prof_image+'" alt=""></div><div class="media-body"><h4 class="media-heading">'+fullname+'</h4><p class="list-group-item-text">'+msg+'<span class="label label-primary pull-right">Date:Now</span></p></div></li>';
					//$(elem).parent('li').append(html);
						$( html ).insertAfter( $(elem).parent('li'));
					}
					
					
				}
			});
		});
	

	//});
	</script>
