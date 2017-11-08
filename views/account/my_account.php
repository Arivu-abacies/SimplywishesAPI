<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'My Account';
$this->params['breadcrumbs'][] = $this->title;
?>

<!--<script src="<?= Yii::$app->request->baseUrl?>/js/html5imageupload.js" type="text/javascript"></script>
 <link href="<?= Yii::$app->request->baseUrl?>/css/html5imageupload.css" rel="stylesheet">-->
 
<?php echo $this->render('_profilenew',['user'=>$user,'profile'=>$profile])?>
 
<div class="site-contact">
    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>

        <div class="row">

            <div class="col-lg-8">
		<?php if(Yii::$app->session->getFlash('success')!='') {?>
			<!--<div class="alert alert-success" role="alert">
				<strong> <?= Yii::$app->session->getFlash('success'); ?>.</strong>
			</div>-->
	
		<?php }
					$form = ActiveForm::begin(['id' => 'contact-form','options' => ['enctype'=>'multipart/form-data']]); ?>

                    <?= $form->field($user, 'username')->textInput(['autofocus' => true,'disabled' => true]) ?>

                    <?= $form->field($user, 'email') ?>

                    <?= $form->field($profile, 'firstname') ?>
					
					<?= $form->field($profile, 'lastname') ?>
					
                    <?= $form->field($profile, 'about')->textarea(['rows' => 6]) ?>
					
					<div class="col-lg-4">
						<?= $form->field($profile, 'country')->dropDownList($countries,[
							'prompt'=>'--Select Country--',
							'id' => 'country_select',
							'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('site/get-states?country_id=').'"+$(this).val(), function( data ) 
							{
								$( "select#state_select" ).html( data ).change();
										
							});'
							]) ?>
					</div>
					
					<div class="col-lg-4">
						<?= $form->field($profile, 'state')->dropDownList($states,[
							'id' => 'state_select',
							'prompt'=>'--Select State--',
							'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('site/get-cities?state_id=').'"+$(this).val(), function( data ) 
							{
								$( "select#city_select" ).html( data ).change();
										
							});'
						]); ?>
					</div>
					
					<div class="col-lg-4">
						<?= $form->field($profile, 'city')->dropDownList($cities,[
							'id' => 'city_select',
							'prompt'=>'--Select City--',
						]); ?>
					</div>
					<img src="<?= \Yii::$app->homeUrl.$profile->profile_image;?>" width="150" height="150" />
					
					<?php  echo $form->field($profile, 'profile_image')->fileInput(['class' => 'form-control'])->label('Upload Profile Image')   ?>
					
					
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
	
   <?= $form->field($profile, 'dulpicate_image')->hiddenInput()->label(false) ?>	
   
					<h3  class="fnt-green" > Change Password </h3>
					<p> Change password if you want to, or leave it empty</p>
					
					<?= $form->field($user, 'password')->passwordInput() ?>
					
					<?= $form->field($user, 'verify_password')->passwordInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

</div>

</div>

<script type="text/javascript" >
$('.profilelogo').click(function(){
 $('.profilelogo').find( "img" ).removeClass('selected'); 
  var val = $(this).attr('for');
  $(this).find( "img" ).addClass('selected'); 
  $("#userprofile-dulpicate_image").val(val);
});


/* 
$('.dropzone').html5imageupload({
		ghost: false,
	}); */

</script>


