<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Wish */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-12">
    <?php $form = ActiveForm::begin(['id' => 'draft_form']); ?>

    <?= $form->field($model, 'category')->dropDownlist($categories,['prompt'=>'--Select--']) ?>

    <?= $form->field($model, 'wish_title')->textInput(['maxlength' => true]) ?>

    <?php // $form->field($model, 'summary_title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'wish_description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]); ?>

	<?php if(!empty($model->primary_image)) {  ?>
		 <img src="<?= \Yii::$app->homeUrl.$model->primary_image;?>" width="150" height="150" />
	<?php } ?>	
	

    <?php  echo $form->field($model, 'primary_image')->fileInput(['class' => 'form-control',"onChange"=>"upload();return false;"]) ?>

	<?php echo $form->field($model, 'primary_image_name')->hiddenInput(['value'=>(!empty($model->primary_image))?$model->primary_image:"images/wish_default/image1.jpg"])->label(false); ?>
	
</br>
      <span>Or Choose One</span>         
      <div class="gravatar thumbnail" style="width:101% !important">
        <a class="profilelogo" style="width: 150px;!important" for="images/wish_default/image1.jpg" ><img style="width: 100%;!important" src="<?=Yii::$app->homeUrl?>images/wish_default/image1.jpg"/></a>
		<a class="profilelogo" style="width: 150px;!important"for="images/wish_default/image2.jpg" ><img style="width: 100%;!important" src="<?=Yii::$app->homeUrl?>images/wish_default/image2.jpg"/></a>
		<a class="profilelogo" style="width: 150px;!important" for="images/wish_default/image3.jpg" ><img style="width: 100%;!important" src="<?=Yii::$app->homeUrl?>images/wish_default/image3.jpg"/></a>					
	  </div>
	  
	<div class="row">
					<div class="col-lg-4">
						<?= $form->field($model, 'country')->dropDownList($countries,[
							'prompt'=>'--Select Country--',
							'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('site/get-states?country_id=').'"+$(this).val(), function( data ) 
							{
								$( "select#state_select" ).html( data ).change();
										
							});'
							]) ?>
					</div>
					<div class="col-lg-4">
						<?= $form->field($model, 'state')->dropDownList($states,[
							'id' => 'state_select',
							'prompt'=>'--Select State--',
							'onchange'=>'$.post( "'.Yii::$app->urlManager->createUrl('site/get-cities?state_id=').'"+$(this).val(), function( data ) 
							{
								$( "select#city_select" ).html( data ).change();
										
							});'
						]); ?>
					</div>
					<div class="col-lg-4">
						<?= $form->field($model, 'city')->dropDownList($cities,[
							'id' => 'city_select',
							'prompt'=>'--Select City--',
						]); ?>
					</div>
	</div>
	<div class="row">
			<div class="col-lg-6">
					
			<?= $form->field($model, 'expected_date')->widget(
                    DatePicker::className(),
                    [
						'attribute' => 'expected_date',
						'options' => ['placeholder' => 'Select issue date ...'],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
							'todayHighlight' => true
                        ]
                    ]
                ); ?>
				
			</div>
			<div class="col-lg-6">
				
			</div>
			
	</div>
	
	 <?php  echo $form->field($model, 'non_pay_option')->radioList(['0'=>'Financial','1'=>'Non-financial','2'=>'Decide Later'],['onclick' => "$(this).val($('input:radio:checked').val())"])->label(false); ?>
	 
	<!-- Financial Begin --->
	
		<?= $form->field($model, 'expected_cost')->textInput(['maxlength' => true])?>
		<div id="expected_cost_check" style="display:none; color:#a94442;" >Expected Cost Is empty check</div>
	<!-- Financial End --->
	
	<!-- NON Financial Begin --->
		
	<?php 
		if($model->isNewRecord){ 
			$model->show_mail_status = "1"; 
			$model->show_person_status = "1"; 
			$model->show_reserved_status = "1"; 
			$model->show_other_status = "1"; 
		}	
	?>
	<?= $form->field($model, 'show_mail_status')->checkbox(['value' =>'1','class'=>"checkall",'for'=>'show_mail_class']);	?>
	<div id="wish-show_mail_status_check" style="display:none; color:#a94442;" >Please Check this field </div>
	<?= $form->field($model, 'show_mail')->textInput(['maxlength' => true,'class'=>'form-control check_test','placeholder' => 'xxx@abc.com']) ?>
	<div id="wish-show_mail_check" class="show_mail_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>
	
	<?= $form->field($model, 'show_person_status')->checkbox(['value' => '1','class'=>"checkall",'for'=>'show_person_class']);	?>
	<div id="wish-show_person_status_check" style="display:none; color:#a94442;" >Please Check this field </div>
	<div class="row">
			<div class="col-lg-6">
	<?= $form->field($model, 'show_person_location')->textInput(['maxlength' => true,'class'=>'form-control check_test']) ?>
	<div id="wish-show_person_location_check" class="show_person_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>
	</div>
	<div class="col-lg-6">
		
	<?= $form->field($model, 'show_person_date')->widget(
                    DatePicker::className(),
                    [
						'attribute' => 'expected_date',
						'options' => ['placeholder' => 'Select issue date ...','class'=>'form-control check_test'],						
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
							'todayHighlight' => true
                        ]
                    ]
                ); ?>
		
<div id="wish-show_person_date_check" class="show_person_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>		
	</div>
			</div>
	<?= $form->field($model, 'show_reserved_status')->checkbox(['value' => '1','class'=>"checkall",'for'=>'show_reserved_class']);	?>
		<div id="wish-show_reserved_status_check" class="show_person_class" style="display:none; color:#a94442;" >Please Check this field </div>
	<?= $form->field($model, 'show_reserved_name')->textInput(['maxlength' => true,'class'=>'form-control check_test']) ?>
	<div id="wish-show_reserved_name_check" class="show_reserved_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>
	<div class="row">
	<div class="col-lg-6">
	<?= $form->field($model, 'show_reserved_location')->textInput(['maxlength' => true,'class'=>'form-control check_test']) ?>
	<div id="wish-show_reserved_location_check" class="show_reserved_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>
	</div>
	<div class="col-lg-6">
	<?= $form->field($model, 'show_reserved_date')->widget(
                    DatePicker::className(),
                    [
						'attribute' => 'expected_date',
						'options' => ['placeholder' => 'Select issue date ...','class'=>'form-control check_test'],
						
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
							'todayHighlight' => true
                        ]
                    ]
                ); ?>
	<div id="wish-show_reserved_date_check" class="show_reserved_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>			
	</div>
			</div>
	<?= $form->field($model, 'show_other_status')->checkbox(['value' => '1','class'=>"checkall",'for'=>"show_other_class"]);	?>
		<div id="wish-show_other_status_check" style="display:none; color:#a94442;" >Please Check this field </div>
	<?= $form->field($model, 'show_other_specify')->textInput(['maxlength' => true,'class'=>'form-control check_test']) ?>
	<div id="wish-show_other_specify_check" class="show_other_class" style="display:none; color:#a94442; margin-left:50px" >Please Fill this field </div>
	
	<div class="form-group" id="agree_check" >	
		<div id="i_agree_decide_req" style="display:none; color:#a94442;" >Please Check this field </div>
		<input type="checkbox"  class="msg" name="Wish[i_agree_decide]" id="i_agree_decide" value="1" <?php echo ($model->i_agree_decide == 1)?"checked":"" ?>> I understand that the grantor will fulfill this wish in the manner specified by you within one month of the date that the grantor accepts this wish. In the meanwhile, this wish will be marked as "In Progress" and after one month, it will be marked as "Fulfilled". You should update or ressubmit your wish if it has not been fulfilled after one month. </input>
	</div>
	
	<div class="form-group" id="agree_check2" >	
		<div id="i_agree_decide_req2" style="display:none; color:#a94442;" >Please Check this field </div>
		<input type="checkbox"  class="msg" name="Wish[i_agree_decide2]" id="i_agree_decide2" value="1" <?php echo ($model->i_agree_decide2 == 1)?"checked":"" ?> > I understand that the grantor will fulfill this wish in the manner specified by you within one month of the date that the grantor accepts this wish. In the meanwhile, this wish will be marked as "In Progress" and after one month, it will be marked as "Fulfilled". You should update or ressubmit your wish if it has not been fulfilled after one month. </input>
	</div>
	
	<!-- NON Financial End --->

	
	<?= $form->field($model, 'who_can')->textArea()?>
	<?= $form->field($model, 'in_return')->textArea()?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?php 
		 if($model->isNewRecord)
			echo $form->field($model, 'auto_id')->hiddenInput()->label(false)  
	?>
	
    <?php ActiveForm::end(); ?>
</div>
</div>

<script type="text/javascript" >
$( document ).ready(function() {
	// Temp this Option is avaliable
	//$(".field-wish-non_pay_option").hide();	
/* 	$("#wish-expected_cost").removeAttr("readonly");	
	
	
     $("#wish-non_pay_option").change(function(){
		 if($(this).prop("checked") == true){
			$("#wish-expected_cost").attr("readonly","readonly");	
		 }
		 else if($(this).prop("checked") == false){
			 $("#wish-expected_cost").removeAttr("readonly");	
		 }
	}); */
	
	$(".field-wish-expected_cost").hide();
	$(".field-wish-show_mail_status").hide();
	$(".field-wish-show_person_status").hide();
	$(".field-wish-show_reserved_status").hide();
	$(".field-wish-show_other_status").hide();
	$(".field-wish-show_mail").hide();
	$(".field-wish-show_person_location").hide();
	$(".field-wish-show_person_date").hide();
	$(".field-wish-show_reserved_name").hide();
	$(".field-wish-show_reserved_location").hide();
	$(".field-wish-show_reserved_date").hide();
	$(".field-wish-show_other_specify").hide();
	$("#agree_check").hide();
	$("#agree_check2").hide();
			
	$(".field-wish-show_mail").css("margin", " 0 0 0 50px");
	$(".field-wish-show_person_location").css("margin", " 0 0 0 50px");
	$(".field-wish-show_person_date").css("margin", " 0 0 0 50px");
	$(".field-wish-show_reserved_name").css("margin", " 0 0 0 50px");
	$(".field-wish-show_reserved_location").css("margin", " 0 0 0 50px");
	$(".field-wish-show_reserved_date").css("margin", " 0 0 0 50px");
	$(".field-wish-show_other_specify").css("margin", " 0 0 0 50px");
	
     $("#wish-non_pay_option").on("change",function(){
			var pay_option = $(this).val();
			if(parseInt(pay_option) == parseInt('0'))
			{
				$(".field-wish-expected_cost").show();
				$(".field-wish-show_mail_status").hide();
				$(".field-wish-show_person_status").hide();
				$(".field-wish-show_reserved_status").hide();
				$(".field-wish-show_other_status").hide();
				$(".field-wish-show_mail").hide();
				$(".field-wish-show_person_location").hide();
				$(".field-wish-show_person_date").hide();
				$(".field-wish-show_reserved_name").hide();
				$(".field-wish-show_reserved_location").hide();
				$(".field-wish-show_reserved_date").hide();
				$(".field-wish-show_other_specify").hide();
				$("#agree_check").hide();
				$("#agree_check2").hide();
			} else if(parseInt(pay_option) == parseInt('1'))
			{
				$(".field-wish-expected_cost").hide();
				$(".field-wish-show_mail_status").show();
				$(".field-wish-show_person_status").show();
				$(".field-wish-show_reserved_status").show();
				$(".field-wish-show_other_status").show();
				$(".field-wish-show_mail").show();
				$(".field-wish-show_person_location").show();
				$(".field-wish-show_person_date").show();
				$(".field-wish-show_reserved_name").show();
				$(".field-wish-show_reserved_location").show();
				$(".field-wish-show_reserved_date").show();
				$(".field-wish-show_other_specify").show();
				$("#agree_check").show();  
				$("#agree_check2").hide();  
				
			} else if(parseInt(pay_option) == parseInt('2'))
			{
				
				$(".field-wish-expected_cost").hide();
				$(".field-wish-show_mail_status").hide();
				$(".field-wish-show_person_status").hide();
				$(".field-wish-show_reserved_status").hide();
				$(".field-wish-show_other_status").hide();
				$(".field-wish-show_mail").hide();
				$(".field-wish-show_person_location").hide();
				$(".field-wish-show_person_date").hide();
				$(".field-wish-show_reserved_name").hide();
				$(".field-wish-show_reserved_location").hide();
				$(".field-wish-show_reserved_date").hide();
				$(".field-wish-show_other_specify").hide();
				$("#agree_check2").show();
				$("#agree_check").hide();
			}				
			
		});
	
	
	<?php 
			if((!$model->isNewRecord) && ($model->non_pay_option == 0))
			{ ?>
				$("#wish-non_pay_option").val(0);
				$(".field-wish-expected_cost").show();
				$(".field-wish-show_mail_status").hide();
				$(".field-wish-show_person_status").hide();
				$(".field-wish-show_reserved_status").hide();
				$(".field-wish-show_other_status").hide();
				$(".field-wish-show_mail").hide();
				$(".field-wish-show_person_location").hide();
				$(".field-wish-show_person_date").hide();
				$(".field-wish-show_reserved_name").hide();
				$(".field-wish-show_reserved_location").hide();
				$(".field-wish-show_reserved_date").hide();
				$(".field-wish-show_other_specify").hide();
				$("#agree_check").hide();
				$("#agree_check2").hide();	
	<?php } else if((!$model->isNewRecord) && ($model->non_pay_option == 1)){ ?>
			$("#wish-non_pay_option").val(1);
			$(".field-wish-expected_cost").hide();
			$(".field-wish-show_mail_status").show();
			$(".field-wish-show_person_status").show();
			$(".field-wish-show_reserved_status").show();
			$(".field-wish-show_other_status").show();
			$(".field-wish-show_mail").show();
			$(".field-wish-show_person_location").show();
			$(".field-wish-show_person_date").show();
			$(".field-wish-show_reserved_name").show();
			$(".field-wish-show_reserved_location").show();
			$(".field-wish-show_reserved_date").show();
			$(".field-wish-show_other_specify").show();
			$("#agree_check").show();  
			$("#agree_check2").hide();
				
	<?php } else if((!$model->isNewRecord) && ($model->non_pay_option == 2)){ ?>
			$("#wish-non_pay_option").val(2);
			$(".field-wish-expected_cost").hide();
			$(".field-wish-show_mail_status").hide();
			$(".field-wish-show_person_status").hide();
			$(".field-wish-show_reserved_status").hide();
			$(".field-wish-show_other_status").hide();
			$(".field-wish-show_mail").hide();
			$(".field-wish-show_person_location").hide();
			$(".field-wish-show_person_date").hide();
			$(".field-wish-show_reserved_name").hide();
			$(".field-wish-show_reserved_location").hide();
			$(".field-wish-show_reserved_date").hide();
			$(".field-wish-show_other_specify").hide();
			$("#agree_check2").show();
			$("#agree_check").hide();
	<?php } ?>
	
	
$( "#draft_form" ).submit(function( event ) {
			
		var pay_option = $("#wish-non_pay_option").val();
		
		if(parseInt(pay_option) == parseInt('1'))
		{	
			//********************* Begin *******************/	
			var check = false;
			var show_mail = $("#wish-show_mail").val();
			if($.trim(show_mail) !== "")
			{
				if($("#wish-show_mail_status").prop("checked") == false){
					$("#wish-show_mail_status_check").show();
					check = true;
				}
			}
			
			var show_person_location = $("#wish-show_person_location").val();
			var show_person_date = $("#wish-show_person_date").val();
			if(($.trim(show_person_location) !== "") || ($.trim(show_person_date) !== "") )
			{
				if($("#wish-show_person_status").prop("checked") == false){
					$("#wish-show_person_status_check").show();
					check = true;
				}
			}
			
			var show_reserved_name = $("#wish-show_reserved_name").val();
			var show_reserved_location = $("#wish-show_reserved_location").val();
			var show_reserved_date = $("#wish-show_reserved_date").val();
			if(($.trim(show_reserved_name) !== "") || ($.trim(show_reserved_location) !== "") || ($.trim(show_reserved_date) !== "")  )
			{
				if($("#wish-show_reserved_status").prop("checked") == false){
					$("#wish-show_reserved_status_check").show();
					check = true;
				}
			}
			
		
			var show_other_specify = $("#wish-show_other_specify").val();
			if($.trim(show_other_specify) !== "")
			{
				if($("#wish-show_other_status").prop("checked") == false){
					$("#wish-show_other_status_check").show();
					check = true;
				}
			}								
			if($("#i_agree_decide").prop("checked") == false){
				$("#i_agree_decide_req").show();
				check = true;
			}
					
			
			
			//********************* End *******************/
			//********************* Begin *******************/
			
		
			
			if($("#wish-show_mail_status").prop("checked") == true)
			{
				var show_mail = $("#wish-show_mail").val();
				if($.trim(show_mail) == "")
				{
					$("#wish-show_mail"+"_check").show();
					check = true;
				}
			}
			
			if($("#wish-show_person_status").prop("checked") == true)
			{
				var show_person_location = $("#wish-show_person_location").val();
				if($.trim(show_person_location) == "")
				{
					$("#wish-show_person_location"+"_check").show();
					check = true;
				}
				var show_person_date = $("#wish-show_person_date").val();
				if($.trim(show_person_date) == "")
				{
					$("#wish-show_person_date"+"_check").show();
					check = true;
				}
			}
			
			if($("#wish-show_reserved_status").prop("checked") == true)
			{
				
				var show_reserved_name = $("#wish-show_reserved_name").val();
				if($.trim(show_reserved_name) == "")
				{
					$("#wish-show_reserved_name"+"_check").show();
					check = true;
				}
				
				var show_reserved_location = $("#wish-show_reserved_location").val();
				if($.trim(show_reserved_location) == "")
				{
					$("#wish-show_reserved_location"+"_check").show();
					check = true;
				}
				var show_reserved_date = $("#wish-show_reserved_date").val();
				if($.trim(show_reserved_date) == "")
				{
					$("#wish-show_reserved_date"+"_check").show();
					check = true;
				}
			}
			
			
			if($("#wish-show_other_status").prop("checked") == true)
			{
				var show_other_specify = $("#wish-show_other_specify").val();
				if($.trim(show_other_specify) == "")
				{
					$("#wish-show_other_specify"+"_check").show();
					check = true;
				}
			}
			
			if(check == true)
			{				
				return false;
			}
			
			if($('.checkall:checkbox:checked').length == 0)
			{
				alert("Please Choose any field For Contact.")
				return false;
			}
			
			//********************* End *******************/
			
		} 
		else if(parseInt(pay_option) == parseInt('2'))
		{
			if($("#i_agree_decide2").prop("checked") == false){
				$("#i_agree_decide_req2").show();
				return false;
			}
						
		}
		else if(parseInt(pay_option) == parseInt('0'))
		{
			var expected_cost = $("#wish-expected_cost").val();
			if($.trim(expected_cost) == ""){
				$("#expected_cost_check").show();
				return false;
			}
						
		}
		
	});
		
		 $("#wish-expected_cost").change(function(){
			var expected_cost = $(this).val();
			if($.trim(expected_cost) != ""){
				$("#expected_cost_check").hide();
			}						 
	    });
		
	    $("#i_agree_decide2").change(function(){
			if($("#i_agree_decide2").prop("checked") == true){
						$("#i_agree_decide_req2").hide();
			}						 
	    });
	
	    $("#i_agree_decide").change(function(){
			if($("#i_agree_decide").prop("checked") == true){
						$("#i_agree_decide_req").hide();
			}						 
	    });
	
	
		$(".checkall").change(function(){
			var id = $(this).attr("id");
			var forid = $(this).attr("for");
			if($("#"+id).prop("checked") == true){
				$("#"+id+"_check").hide();
			} else 
			{					
				$("."+forid).hide();
			}				
	    });
		
		$(".check_test").change(function(){
			var id = $(this).attr("id");
			if($("#"+id).val() != ""){
				$("#"+id+"_check").hide();
			}						 
	    });
		
});
</script>

<script type="text/javascript" >
 function upload(){
  data = new FormData();
  data.append('file', $('#wish-primary_image')[0].files[0]);
  var imgname  =  $('#wish-primary_image').val();
  var size  =  $('#wish-primary_image')[0].files[0].size;
  var ext =  imgname.substr( (imgname.lastIndexOf('.') +1) );
  var display_name = imgname.substr( (imgname.lastIndexOf('\\') +1) );
  $.ajax({
        url: 'upload-file',
        type: "POST",
        data: data,
        enctype: 'multipart/form-data',
        processData: false,  // tell jQuery not to process the data
        contentType: false,
        success: function(json){      
			$("#wish-primary_image_name").val(json);
			
        }
  });
}
 
</script>

<script type="text/javascript"> 
  $(function(){				
		$('.profilelogo').click(function(){
				 $('.profilelogo').find( "img" ).removeClass('selected'); 
				  var val = $(this).attr('for');
				  $(this).find( "img" ).addClass('selected'); 
				  $("#wish-primary_image_name").val(val);
		});

  });  
  </script>
  
<style>
#wish-non_pay_option label{
	margin-left : 25px  !important
}

</style>
