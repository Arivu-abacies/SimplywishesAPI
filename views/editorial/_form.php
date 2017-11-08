<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Editorial */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(\Yii::$app->homeUrl."css/waitingfor.js");
?>

<div class="editorial-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'e_title')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'e_text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic',
	
    ]); ?>		
    <?php // $form->field($model, 'e_image')->fileInput(['class' => 'form-control']) ?>

	
	<?php if($model->isNewRecord){ 
		  echo $form->field($model, 'e_image')->fileInput(['class'=>'form-control']);
	 } else if($model->e_image != '' ) { ?>	
		<div class="form-group field-company-logo" >
			<label class="control-label" >Image</label>
				<img id='imagesorce' src="<?=Yii::$app->homeUrl.$model->e_image ?>" height="100px" />
				<a style="cursor:pointer" class="removelogo" >Change Image</a>
		</div>	
	<?php 	
			
			echo $form->field($model, 'e_image')->fileInput(['class'=>'form-control','style'=>'display:none'])->label(False);
		} else { 	
			echo $form->field($model, 'e_image')->fileInput(['class'=>'form-control']);
	} ?>
		 

		 
		 <h4> Video</h4>
			<p><?php if(!$model->isNewRecord && $model->featured_video_url != ''){ 
							$url = $model->featured_video_url; 
										
					if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
							echo "<iframe width='600' height='300' src=".$url." controls></iframe>";
					} else {
							echo $url;
					}
					}
			else echo 'This is used on the Editorial Upload Video and Embed URL';
			?></p>
			<div class="col-md-5">
				<?= $form->field($model, 'featured_video_url')->textInput(['class'=>'form-control','onChange'=>'saveVideoUrl(this)','id'=>'video_url'])->label(false) ?>
				</div>
				<div class="col-md-1"> ( Or ) </div> 
				<div class="col-md-6">
					<?php echo $form->field($model, 'featured_video_upload')->fileInput(['onChange'=>'saveFile(this)','class'=>'form-control'])->label(false) ?>
				</div>
									
									
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

 <script type="text/javascript"> 
  $(function(){	

			$('#editorial-e_image').change( function(event) {
				var tmppath = URL.createObjectURL(event.target.files[0]);
				$("#imagesorce").fadeIn("fast").attr('src',tmppath);
			});
  });  
  </script>
  
  
<script type="text/javascript" >
$(".removelogo").click(function() {	
				$( "#editorial-e_image" ).click();
		  });
</script>


<script type="text/javascript" >

<!---------- Save file -------------------->
function saveFile(input){

 	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();
	file = input.files[0];
	var ext = input.files[0]['name'].substring(input.files[0]['name'].lastIndexOf('.') + 1).toLowerCase();	
 	if(file != undefined){
		
		waitingDialog.show('Uploading..');
	 formData= new FormData();
	if(ext == "mp4" || ext == "m4v" || ext == "webm" || ext == "ogv"){
		formData.append("media", file); 
		$.ajax({
			url: "<?=Url::to(['editorial/upload'])?>",
			type: "POST",
			data: formData,
			processData: false,
			contentType: false,
			success: function(data){
				waitingDialog.hide();
				//$(input).attr('src', data);
				$('#video_url').val(data);
			}
		}); 
 	}else{
		alert("Extension not supported");
		waitingDialog.hide();
		return false;
	} 


	} else {
		alert("file Input Error");
	}
}
//($('.fld-description').val()).length;
<!---------- End of save file ------------->
function saveVideoUrl(input){

	var url = $(input).val();
	if(url!=''){
	waitingDialog.show('Fetching..');
		$.ajax({
			url: "<?=Url::to(['editorial/embed'])?>?url="+url,
			type: "GET",
			processData: false,
			contentType: false,
			success: function(data){
				waitingDialog.hide();
				$('#video_url').val(data);
			},
			 error:function(data){
				//alert("Oops!Something wrong happend. Please try again later");
				waitingDialog.hide();
			} 
		});		
	}
}




</script>

