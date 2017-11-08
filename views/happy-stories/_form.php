<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Wish;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Editorial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="happy-stories-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

   
		<?php
		$user_id = \Yii::$app->user->id;
		$wish = ArrayHelper::map(Wish::find()->where(['wished_by'=>$user_id])->andwhere(['!=','granted_by',''])->orderBy('wish_title')->all(), 'w_id', 'wish_title');
			echo $form->field($model, 'wish_id')->dropDownList(
            $wish,           // Flat array ('id'=>'label')
            ['prompt'=>'--Wishes List--']    // options
        )->label('Wishes');  ?>
		
		 
		<?= $form->field($model, 'story_text')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]); ?>		 
   

   
	<?php if(!empty($model->story_image)) {  ?>
		 <img src="<?= \Yii::$app->homeUrl.$model->story_image;?>" width="150" height="150" />
	<?php } ?>	 
	 <?php 		 
			echo $form->field($model, 'story_image')->fileInput(['class'=>'form-control'])->label('Image');		
		?>
	</br>
      <span>Or Choose One</span>         
      <div class="gravatar thumbnail" style="width:101% !important">
        <a class="profilelogo" style="width: 150px;!important" for="images/happy1.jpg" ><img style="width: 126px;!important" src="<?=Yii::$app->homeUrl?>images/happy1.jpg"/></a>
		<a class="profilelogo" style="width: 150px;!important"for="images/happy2.jpg" ><img style="width: 150px;!important" src="<?=Yii::$app->homeUrl?>images/happy2.jpg"/></a>
		<a class="profilelogo" style="width: 150px;!important" for="images/happy3.jpg" ><img style="width: 150px;!important" src="<?=Yii::$app->homeUrl?>images/happy3.jpg"/></a>					
	  </div>
	  
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

	<?= $form->field($model, 'dulpicate_image')->hiddenInput(['value'=>($model->story_image)?$model->story_image:'images/happy1.jpg'])->label(false) ?>	
	
    <?php ActiveForm::end(); ?>

</div>

 <script type="text/javascript"> 
  $(function(){	
			/* $('#happystories-story_image').change( function(event) {
				var tmppath = URL.createObjectURL(event.target.files[0]);
				$("#imagesorce").fadeIn("fast").attr('src',tmppath);
			}); */
			
			$('.profilelogo').click(function(){
				 $('.profilelogo').find( "img" ).removeClass('selected'); 
				  var val = $(this).attr('for');
				  $(this).find( "img" ).addClass('selected'); 
				  $("#happystories-dulpicate_image").val(val);
			});

  });  
  </script>
  
<script>
/* $(".removelogo").click(function() {	
				$( "#happystories-story_image" ).click();
		  }); */
</script>