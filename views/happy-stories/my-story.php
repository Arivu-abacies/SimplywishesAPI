<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\UserProfile;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Happy Wish Stories';
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('@app/views/account/_profilenew',['user'=>$user,'profile'=>$profile])?>

<script src="<?= Yii::$app->request->baseUrl?>/src/masonry.js" type="text/javascript"></script>
<script src="<?= Yii::$app->request->baseUrl?>/src/imagesloaded.js" type="text/javascript"></script>

<div class="col-md-8 editorial-create smp-mg-bottom">

	<h3 class="fnt-green smp-mg-bottom"  > My Happy Stories</h3>
	
	<?php if (Yii::$app->session->hasFlash('success_happystory')): ?>

		<div class="alert alert-success" style="margin-top: 20px;  width: 80%;">
			Your Story is Updated Successfully.	
		</div>
	<?php endif; ?>	
	
	<?php if (Yii::$app->session->hasFlash('success_adminhappystory')): ?>

		<div class="alert alert-success" style="margin-top: 20px;  width: 80%;">
			Your Story is Created Successfully waiting for Site Approvel.
		</div>
	<?php endif; ?>	

	<a class='btn btn-success pull-right newtest' style="margin-top: -60px;" href="<?=Yii::$app->homeUrl?>happy-stories/create">Tell Your Story</a> 

	<div class="grid" data-masonry='{ "itemSelector": ".grid-item" }'>
		
		<?php
		
		if(isset($dataProvider->models) && !empty($dataProvider->models))
		{		
			foreach($dataProvider->models as $story)
			{
				$wish_details = $story->wish;
				/* echo "<pre>";
				print_r($wish_details->wish_title);
				exit; */
				
				$profile = UserProfile::find()->where(['user_id'=>$story->user_id])->one();		

				?>
				
				<div class="col-md-12 happystory smp-mg-bottom">
					<div class="media"> 
						<div class="media-left"> 
							<img alt="64x64" src="<?=Yii::$app->homeUrl?><?= $story->story_image; ?>" class="media-object"   style="width: 200px;border: solid 2px #0cb370;">
							<span ><i class="fa fa-thumbs-o-up fnt-green"></i>  <?=$story->likesCount?>  Likes </span>
						</div> 
						<div class="media-body"> 
							<?php if(\Yii::$app->user->id == $story->user_id){ ?>
								<div class="pull-right" >
									<?= Html::a('<i class="fa fa-pencil" aria-hidden="true"></i> Update', ['update', 'id' => $story->hs_id], ['class' => 'btn btn-warning']) ?>


								</div> 
								<?php } ?>
								<h4 class="media-heading"><?php echo $wish_details['wish_title']; ?></h4>
								
								<a href="<?= Url::to(["account/profile","id"=>$story->user_id]) ?>">Author: <?= $story->author->fullname; ?></a>
								<p> <?=substr($story->story_text,0,450)?></p>
								<a href="<?=Yii::$app->homeUrl?>happy-stories/story-details?id=<?= $story->hs_id; ?>" ><h5>Read More</h5></a>
							</div> 
						</div>
					</div>

					<?php
				}
			} else {
				echo "Sorry, No more Happy Stories from you.";
			}	
			?>

		</div>
	</div>

	<div style="display:none" align="center" id="loader_img" ><img src="<?= Yii::$app->homeUrl?>images/loading2.gif"></div>
	
</div>
<script type="text/javascript">
	$(document).on('click', '.deletecheck', function(){ 
		var checkmsg = confirm("Are Sure To Delete this Happy Story ?");	
		if(checkmsg == false)
		{
			return false;
		}	
		var id = $(this).attr('for');
		$.ajax({
			url : '<?=Url::to(['happy-stories/delete'])?>',
			type: 'POST',
			data: { id:id },
			success:function(data){
				location.reload();
			}
		});
	});
</script>

<script>
	$(window).load(function() {
		var win = $(window);
		var page = 1;
		var page2 = 1;
		var $container = $('.grid');
		$container.masonry();
  	// Each time the user scrolls
  	win.scroll(function() {
  		console.log($(document).height() - win.height(),"total");
  		var scroll_top = Math.round(win.scrollTop());
  		console.log(scroll_top,"top");
  		// End of the document reached?
  		if(parseInt(page) == parseInt(page2)){
  		//if ($(document).height() - win.height()-1 == scroll_top ) {
  			if($(win).scrollTop() + $(win).height() == $(document).height()){
  				console.log("scrolld");
  				page2 = page2+1;
  				$("#loader_img").show();

  				$.ajax({
  					url: '<?=Url::to(['happy-stories/scroll-my-happy'], true);?>',
  					dataType: 'html',
  					data: {'page':page},
  					success: function(html) {
  						var el = $(html);
  					//$(".grid").append(el).masonry( 'appended', el, false );
  					var $newElems = $( html ).css({ opacity: 0 });
  					$newElems.imagesLoaded(function(){
  						$newElems.animate({ opacity: 1 });
  						$(".grid").append(el);

  						$(".grid").masonry( 'appended', el, true )
  					});
  					if(html == '')
  						page = false;
  					else
  						page = page+1;

  					$("#loader_img").hide();
  				},
  				error:function(){
  					$("#loader_img").hide();
  				}
  			}); 


  			//$container.masonry();
  			
  		}}
  	});

  });
	
</script>	