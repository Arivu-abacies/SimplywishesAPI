<?php 
use yii\helpers\Url;
?>

	<div class="simply-head"><h3 class="fnt-green">Fullfilled Wishes</h3></div>
	
	<div class="container-fluid">    
		<section class="regular slider">
			<?php foreach($models as $model){
				echo '<div class="smpl-wish-block thumbnail">';
				echo '<div><a href="'.Url::to(['wish/view','id'=>$model->w_id]).'"><img src="'.\Yii::$app->homeUrl.$model->primary_image.'" class="img-responsive" alt="Image"></a></div>';
				  /////activities///
				  if(!$model->isFaved(\Yii::$app->user->id))
					echo  '<div class="smp-links sharefull-list"><span title="Save this wish" data-w_id="'.$model->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-orange"></span></br>';
				  else
					echo  '<div class="smp-links sharefull-list"><span title="You saved it" data-w_id="'.$model->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-blue"></span></br>';

				  if(!$model->isLiked(\Yii::$app->user->id))
					echo  '<span title="Like it" data-w_id="'.$model->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-green"></span>';
				  else
					echo  '<span title="You liked it" data-w_id="'.$model->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-pink"></span>';
				  ///////////////////////////////////
				  /** Share Icon Display   ***/
				  
				  echo '<span  data-placement="right"  data-popover-content=""><img data-placement="right" class="listesinside"  src="'.\Yii::$app->homeUrl.'images/Share-Icon.png"  /></span>					
							<div class="shareIcons hide" data_text="'.$model->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$model->w_id],true).'"></div>
						 </div>';
				  
				  ////////////////////////////
				  echo  '<div class="smp-wish-desc">';
					echo  '<p><div class="list-icon">
							<img src="'.$model->wisherPic.'" alt="" />
							<a href="'.Url::to(['account/profile','id'=>$model->wished_by]).'"><span>'.$model->wisherName.'</span></a>
						</div></p>
					<!--<p>Wish For : <span>'.$model->wish_title.'</span></p>
					<p>Location : <span>'.$model->location.'</span></p>-->
					<p class="desc">'.substr($model->wish_title,0,50).'..</p>
					<p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$model->w_id]).'">Read More</a>
					&nbsp;<i class="fa fa-thumbs-o-up fnt-blue"></i> <span id="likecmt_'.$model->w_id.'"  >'.$model->likesCount.'</span> Likes </p>';
					
				  echo  '</div>';
				  
				  echo  '</div>';
			}?>
		</section>
	</div>
	<div class="simply-head">
		<a href="<?=\Yii::$app->homeUrl?>wish/granted"><button class="btn btn-smp-green smpl-brdr" type="button">SEE MORE FULFILLED WISHES</button></a>
	</div>
<script type="text/javascript">
	$(".shareIcons").each(function(){
		var elem = $(this);
			elem.jsSocials({
			showLabel: false,
			showCount: false,
			shares: ["facebook","googleplus", "pinterest", "linkedin","reddit",
			{
				share: "twitter",           // name of share
				via: "simply_wishes",       // custom twitter sharing param 'via' (optional)
				hashtags: "simplywishes,dream_come_true"   // custom twitter sharing param 'hashtags' (optional)
			}],
			url : elem.attr("data_url"),
			text: elem.attr("data_text"),
		});
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
	

</script>

<script type="text/javascript" >
$(document).ready(function(){	
var isVisible = false;
var clickedAway = false;
	$(function(){
		$('.listesinside').popover({   
			html: true,
			content: function () {
				var clone = $($(this).parents(".sharefull-list").find(".shareIcons")).clone(true).removeClass('hide');
				return clone;
			}
		}).click(function(e) {
			//e.preventDefault();
			clickedAway = false;
			isVisible = true;
		});
	});
	/* $(document).on('click','.jssocials-shares',function(){
		 $('.listesinside').popover('hide');
	}); */
	
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

});

/* 
$(document).on("click", ".report-img", function() {
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

</script>

<!--------------- SLIDER CHECK Function ----------------------------------->

 <link rel="stylesheet" type="text/css" href="<?= Yii::$app->homeUrl?>src/slick/slick.css">
 <link rel="stylesheet" type="text/css" href="<?= Yii::$app->homeUrl?>src/slick/slick-theme.css"> 
 <script src="<?= Yii::$app->homeUrl?>src/slick/jquery-2.2.0.min.js" type="text/javascript"></script>
 <script src="<?= Yii::$app->homeUrl?>src/slick/slick.js" type="text/javascript" charset="utf-8"></script>
 
  
 <script type="text/javascript">
 // var js = $.noConflict();
  var js = jQuery.noConflict(true);
   js(document).on('ready', function() {
	  
       js(".regular").slick({
         dots: false,
	 infinite: true,
	 speed: 300,
	 slidesToShow: 5,
	 slidesToScroll: 4,
	  responsive: [
		{
		 breakpoint: 1024,
		 settings: {
			slidesToShow: 3,
			slidesToScroll: 2,
			infinite: true,
			dots: false,
		 }
		},
		{
		 breakpoint: 600,
		 settings: {
			slidesToShow: 2,
			slidesToScroll: 1
		 }
		},
		{
		 breakpoint: 480,
		 settings: {
			slidesToShow: 1,
			slidesToScroll: 1
		 }
		}
	 ] 
      });
      

	
    });
  </script>
  

  <!--------------- SLIDER CHECK Function END ----------------------------------->
  
  