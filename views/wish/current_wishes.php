<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<script src="<?= Yii::$app->request->baseUrl?>/src/masonry.js" type="text/javascript"></script>
<script src="<?= Yii::$app->request->baseUrl?>/src/imagesloaded.js" type="text/javascript"></script>
    <div class="col-md-12 smp-mg-bottom">
		<div class="col-md-3">
			<h3 class="fnt-green" >Find A Wish</h3>
			<ul class="nav list list-group">
				<li class="list-group-item"><a href="<?=\Yii::$app->homeUrl?>wish/popular">Most Popular Wishes</a></li>
				<li class="list-group-item"><a href="<?=\Yii::$app->homeUrl?>wish/granted">Fullfilled Wishes</a></li>
				<?php if($cat_id) 
					echo '<li class="list-group-item"><a href="'.\Yii::$app->homeUrl.'wish/index">Current Wishes</a></li>';
				else 
					echo '<li class="active list-group-item"><a href="#current" data-toggle="tab">Current Wishes</a></li>'			
				?>
				
				<?php 
					if($cat_id) 
					{
						$active = 'test';
					} 
					else 
					{
						$active = ''; 
					}
				?>
				<li class="<?=$active?> list-group-item dropdown">
					<a data-toggle="collapse" data-target="#demo" >Recipient 
					
						<i class="fa fa-plus text-success pull-right" <?php if(!empty($active)){ echo "style='display:none;'"; } ?> ></i>
						<i class="fa fa-minus text-success pull-right" <?php if(empty($active)){ echo "style='display:none;'"; } ?> ></i>
					</a>
					<ul id="demo" class="nav nav-stacked collapsed collapse">
						<?php
							$categories = \app\models\Category::find()->all();
							foreach($categories as $cat){
							echo "<li id='cat_".$cat->cat_id."'><a href='".\Yii::$app->homeUrl."wish/index?cat_id=$cat->cat_id'> $cat->title</a></li>";
							}
						?>
					</ul>
				</li>
			</ul>
			</br>
			<p>Search By Keyword Or Location</p>
			<div class="input-group">
				<input name="searh_field" id="searh_field" type="text" class="form-control" placeholder="City, State, Country.....other">
				<span class="input-group-btn">
				  <button class="search-wish btn btn-default" type="button">
					<span class="glyphicon glyphicon-search"></span>
				  </button>
				</span>
			</div>
		</div>
		<div class="col-md-9">
			<div class="tab-content">
				<div class="tab-pane" id="mostpopular">
					<h3 style="color:#006699;">Most Popular Wishes</h3>					
				</div>
				<div class="tab-pane" id="fullfilled">
					<h3 style="color:#006699;">Fullfilled Wishes</h3>
				</div>
				<div class="tab-pane active"  id="current">
					<h3 class="fnt-green" >Current Wishes
					<?php if($cat_id)
						echo " : ".\app\models\Category::findOne($cat_id)->title; ?>
					</h3>
					<div class="grid"  data-masonry='{ "itemSelector": ".grid-item" }' id="current">
					<?php

					foreach($dataProvider->models as $wish){
						echo $wish->wishAsCard;
					}
?>
					</div>
				</div>
				<div style="display:none" align="center" id="loader_img" ><img src="<?= Yii::$app->homeUrl?>images/loading2.gif"></div>
				<div class="tab-pane" id="recent">
					<h3 style="color:#006699;">Recipient</h3>
				</div>
			</div>
		</div>
	</div>
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
		//  previous line if($(win).scrollTop() + $(win).height() == $(document).height()){
		if($(win).scrollTop() + $(win).height() + parseInt(50) >= $(document).height()){
			console.log("scrolld");
			page2 = page2+1;
			$("#loader_img").show();
  			$.ajax({
  				url: '<?=Url::to(['wish/scroll','cat_id'=>$cat_id], true);?>',
  				dataType: 'html',
  				data: {'page':page},
  				success: function(html) {
  					var el = $(html);
  					//$(".grid").append(el).masonry( 'appended', el, false );
  					var $newElems = $( html ).css({ opacity: 0 });
  					$newElems.imagesLoaded(function(){
  						$newElems.animate({ opacity: 1 });
  						$(".grid").append(el);
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
  			//page = page+1;
	}}
		});

	});
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
	var cat_id = '<?=$cat_id?>';
	if(cat_id){
		$('#demo').addClass('in');
		$('#cat_'+cat_id).addClass('selected');
	}
	//search srcipt
	$(".search-wish").on("click",function(){		
		if($("input[name=searh_field]").val() != ''){
			var url = "<?=Url::to(['wish/search'])?>";
			window.location.href = url+"?match="+$("input[name=searh_field]").val();
		}
		else{
			var url = "<?=Url::to(['wish/index'])?>";
			window.location.href = url;			
		}		
	});
	
	$('#searh_field').keypress(function (e) {
		  if (e.which == 13) {
				$( ".search-wish" ).trigger( "click" );
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

</script>
