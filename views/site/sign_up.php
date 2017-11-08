<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Join Today';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<script src="<?= Yii::$app->request->baseUrl?>/js/html5imageupload.js" type="text/javascript"></script>
 <link href="<?= Yii::$app->request->baseUrl?>/css/html5imageupload.css" rel="stylesheet">-->
 
<div class="row page-header">
  <div class="container join-taday">
	<h3 class="smp-mg-bottom fnt-green"  ><?= Html::encode($this->title) ?></h3>
  
  
  <?php if (Yii::$app->session->hasFlash('RegisterFormSubmitted')){ ?>

        <div class="alert alert-success">
            Your Registration Has Been Successfully Completed,  Please Check Your E-mail for validation. 
        </div>

    <?php } ?>
	
<?php $form = ActiveForm::begin(['id' => 'contact-form','options' => ['class' => 'col-md-8','enctype'=>'multipart/form-data']]); ?>
  
	   <?= $form->field($user, 'username')->textInput(['autofocus' => true])->label("Username <span class='valid-star-color' >*</span> ")?>
	   
	   <?= $form->field($user, 'email')->label("Email Address <span class='valid-star-color' >*</span>") ?>
	 
	   <?= $form->field($profile, 'firstname')->label('First Name <span class="valid-star-color" >*</span>') ?>

	   <?= $form->field($profile, 'lastname')->label('Last Name <span class="valid-star-color" >*</span>') ?>
	
	   <?= $form->field($profile, 'about')->textarea(['rows' => 6])?>
		
	   <div class="col-lg-4">
						<?= $form->field($profile, 'country')->dropDownList($countries,[
							'prompt'=>'--Select Country--',
							'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('site/get-states?country_id=').'"+$(this).val(), function( data ) 
							{
								$( "select#state_select" ).html( data ).change();
										
							});'
							]) ?>
		</div>
		<div class="col-lg-4">
						<?= $form->field($profile, 'state')->dropDownList([],[
							'id' => 'state_select',
							'prompt'=>'--Select State--',
							'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('site/get-cities?state_id=').'"+$(this).val(), function( data ) 
							{
								$( "select#city_select" ).html( data ).change();
										
							});'
						]); ?>
		</div>
		<div class="col-lg-4">
						<?= $form->field($profile, 'city')->dropDownList([],[
							'id' => 'city_select',
							'prompt'=>'--Select City--',
						]); ?>
					</div>
					

				<?= $form->field($user, 'password')->passwordInput() ?>
					
				<?= $form->field($user, 'verify_password')->passwordInput() ?>
					

	
	 <?php   echo  $form->field($profile, 'profile_image')->fileInput(['class' => 'form-control'])->label('Upload Profile Image')  ?>
	 
	 <!--<div class="form-group field-userprofile-profile_image">
		<label class="control-label" for="userprofile-profile_image">Upload Profile Image</label>
		<br></br>
		<input type="hidden" name="UserProfile[profile_image]" value="">
	
		
		
		 <div class="dropzone" data-smaller="true" data-canvas-image-only="true" data-button-done="true"  data-originalsize="false" id="for_image" data-width="300" data-ajax="false" data-height="300" data-button-zoomout="false">
           <input type="file"  id="userprofile-profile_image" class="form-control" name="UserProfile[profile_image]"  />
		</div>
	
		<p class="help-block help-block-error"></p>
	 </div>-->

	  
	  </br>
      <span>Or Choose One</span>         
      <div class="gravatar thumbnail" style="width:101% !important">
        <a class="profilelogo" for="images/img1.jpg" ><img class="selected" src="<?=Yii::$app->homeUrl?>images/img1.jpg"/></a>
		<a class="profilelogo" for="images/img2.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img2.jpg"/></a>
		<a class="profilelogo" for="images/img3.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img3.jpg"/></a>
		<a class="profilelogo" for="images/img4.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img4.jpg"/></a>
		<!--<a class="profilelogo" for="images/img5.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img5.jpg"/></a>-->
		<a class="profilelogo" for="images/img6.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img6.jpg"/></a>
		<a class="profilelogo" for="images/img7.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img7.jpg"/></a>
		<a class="profilelogo" for="images/img8.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img8.jpg"/></a>
		<a class="profilelogo" for="images/img9.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img9.jpg"/></a>
		<a class="profilelogo" for="images/img10.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img10.jpg"/></a>
		<a class="profilelogo" for="images/img11.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img11.jpg"/></a>
		<a class="profilelogo" for="images/img12.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img12.jpg"/></a>
		<a class="profilelogo" for="images/img13.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img13.jpg"/></a>
		<a class="profilelogo" for="images/img14.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img14.jpg"/></a>
		<a class="profilelogo" for="images/img15.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img15.jpg"/></a>
		<a class="profilelogo" for="images/img16.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img16.jpg"/></a>
		<a class="profilelogo" for="images/img17.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img17.jpg"/></a>
		<a class="profilelogo" for="images/img18.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img18.jpg"/></a>
		<a class="profilelogo" for="images/img19.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img19.jpg"/></a>
		<a class="profilelogo" for="images/img20.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img20.jpg"/></a>
		<a class="profilelogo" for="images/img21.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img21.jpg"/></a>
		<a class="profilelogo" for="images/img22.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img22.jpg"/></a>
		<a class="profilelogo" for="images/img23.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img23.jpg"/></a>
		<a class="profilelogo" for="images/img24.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img24.jpg"/></a>
		<a class="profilelogo" for="images/img25.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img25.jpg"/></a>
		<a class="profilelogo" for="images/img26.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img26.jpg"/></a>
		<a class="profilelogo" for="images/img27.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img27.jpg"/></a>
		<a class="profilelogo" for="images/img28.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img28.jpg"/></a>
		<a class="profilelogo" for="images/img29.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img29.jpg"/></a>
		<!--<a class="profilelogo" for="images/img30.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img30.jpg"/></a>-->
		<a class="profilelogo" for="images/img31.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img31.jpg"/></a>
		<a class="profilelogo" for="images/img32.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img32.jpg"/></a>
		<a class="profilelogo" for="images/img33.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img33.jpg"/></a>
		<a class="profilelogo" for="images/img34.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img34.jpg"/></a>
		<a class="profilelogo" for="images/img35.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img35.jpg"/></a>
		<a class="profilelogo" for="images/img36.jpg" ><img src="<?=Yii::$app->homeUrl?>images/img36.jpg"/></a>
		
		
	  </div>
	
   <?= $form->field($profile, 'dulpicate_image')->hiddenInput(['value'=>'images/img1.jpg'])->label(false) ?>	
   
	<div class="checkbox">
	
	<label class="checkbox-inline"><input  type="checkbox" required class="terms" value="">I Agree to the 
			<a data-toggle="modal" data-target="#termsmodal" >Terms Of Use</a> , 
			<a data-toggle="modal" data-target="#communitymodal" >Community Guidlines</a> and 
			<a data-toggle="modal" data-target="#policymodal" >Privacy Policy</a>	
			
		</label>
		
		<!--<label class="checkbox-inline"><input  type="checkbox" required class="terms" value="">
			<span data-toggle="modal" data-target="#termsmodal" >Terms Of Use</span>
		</label>

		<label  class="checkbox-inline"><input  type="checkbox" required class="community" value="">
			<span data-toggle="modal" data-target="#communitymodal" >Community Guidlines</span>
		
		</label>
		<label  class="checkbox-inline"><input   type="checkbox" required class="policy" value="">
			<span data-toggle="modal" data-target="#policymodal" >Privacy Policy</span>	
		</label>-->
	</div></br>
	
	<!-- Terms modal Starts -->
	<div class="modal fade" id="termsmodal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document" style=" width: 80% !important;">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Acceptance of Terms of Use; Modifications</h4>
		  </div>
		  <div class="modal-body">
			<!--<p>SimplyWishes, Inc. (“SimplyWishes” “we” or “us” or “our”) owns and operates the website, 
			<a href="http://www.simplywishes.com">http://www.simplywishes.com</a>, the mobile and touch version and any websites or mobile applications 
			we have now or in the future that reference these Terms of Use (collectively, “Site”). 
			By using the Site and the services available via the Site accesses or uses the Site with crawlers, 
			robots, data mining or extraction tools or any other functionality.</p>-->
				<p><?= $terms->content  ?></p>
		  </div>
		   <!--<div class="modal-footer">
			<button type="button" class="btn btn-primary agree" data-cat="terms" data-id="#termsmodal">Agree</button>
		  </div>-->
		</div>
	  </div>
	</div>
	<!-- Terms modal Ends -->
	
	<!-- Community modal Starts -->
	<div class="modal fade" id="communitymodal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document" style=" width: 80% !important;" >
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Acceptance of Community Guidelines; Modifications</h4>
		  </div>
		  <div class="modal-body">
			<!--<p>By using the Site and the services available via the Site, you agree to this Privacy Policy 
			and any additional terms applicable to certain programs in which you may elect to participate. You also 
			agree to the SimplyWishesTerms of Use, located at <a href="http://www.simplywishes.com/termsofuse">http://www.simplywishes.com/termsofuse</a>, which is 
			incorporated herein by reference and any reference to these Terms of Use herein shall be deemed to reference 
			and include the Terms of Use.</p>-->
			<p><?= $community_guidelines->content  ?></p>
		  </div>
		  <!--<div class="modal-footer">
			<button type="button" class="btn btn-primary agree" data-cat="community" data-id="#communitymodal">Agree</button>
		  </div>-->
		</div>
	  </div>
	</div>
	<!-- Community modal Ends -->
	
	<!-- Privacy Policy Modal Starts -->
	<div class="modal fade" id="policymodal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document" style=" width: 80% !important;">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Acceptance of Privacy Modifications</h4>
		  </div>
		  <div class="modal-body">
			<!--<p>By using the Site and the services available via the Site, you agree to this Privacy Policy and any additional
			terms applicable to certain programs in which you may elect to participate. You also agree to the SimplyWishesTerms
			of Use, located at <a href="http://www.simplywishes.com">http://www.simplywishes.com</a>, which is incorporated herein by reference and any 
			reference to these Terms of Use herein shall be deemed to reference and include the Terms of Use.</p>-->
			
			<p><?= $privacy_policy->content ?></p>
			
		  </div>
		   <!--<div class="modal-footer">
			<button type="button" class="btn btn-primary agree" data-cat="policy" data-id="#policymodal">Agree</button>
		  </div>-->
		</div>
	  </div>
	</div>
	<!-- Privacy Policy Modal Ends -->
	
	  <div class="form-group">
            <?= Html::submitButton('Join', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
      </div>
	  
 <?php ActiveForm::end(); ?>

  </div>
  		
</div>


<script type="text/javascript"  >
jQuery(document).ready(function(){
 $('ul.nav li.dropdown').hover(function() { 
	$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
		}, function() { 
		$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
	}); 
});
 $('.agree').click(function(){ 
 	var cat = $(this).data('cat');
	var id = $(this).data('id');
	 $('.'+cat).prop('checked', true);
	$(id).modal('hide'); 
}); 

$('.profilelogo').click(function(){
 $('.profilelogo').find( "img" ).removeClass('selected'); 
  var val = $(this).attr('for');
  $(this).find( "img" ).addClass('selected'); 
  $("#userprofile-dulpicate_image").val(val);
});

/* $('.dropzone').html5imageupload({
		ghost: false,
	}); */
	
</script>

