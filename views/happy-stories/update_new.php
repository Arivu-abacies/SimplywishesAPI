<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Wish;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Update Your Story';
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->hs_id, 'url' => ['view', 'id' => $model->hs_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class=" col-md-8 editorial-update">
	<h1 class="fnt-green"><?= Html::encode($this->title) ?></h1>
	
<div class="happy-stories-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>

		<?php
		$wish = Wish::find()->where(['w_id'=>$model->wish_id])->orderBy('wish_title')->one();
		 ?>
		
		<?= $form->field($model, 'wish_id')->textInput(['maxlength' => true,'readonly'=> true,'value'=>$wish->wish_title])->label('Wishes') ?>
		  
		<?= $form->field($model, 'story_text')->widget(CKEditor::className(), ['options' => ['rows' => 6,'readonly' => true ],'preset' => 'basic' ]); ?>		 
   

   
	
	<?php if($model->story_image != '' ) { ?>	
		<div class="form-group field-company-logo" >
			<label class="control-label" >Image</label>
				<img id='imagesorce' src="<?=Yii::$app->homeUrl.$model->story_image ?>" height="100px" />				
		</div>	
	<?php 	}  ?>
		 
	
	<div class="form-group field-happystories-status required">
		<label class="control-label" for="happystories-status">Status</label>
		<select id="happystories-status" class="form-control" name="HappyStories[status]">
		<?php if($model->status == 0) { ?>
			<option value="0" selected>Active</option>
			<option value="1">Inactive</option>	
		<?php } else if($model->status == 1){ ?>
			<option value="0" >Active</option>
			<option value="1" selected >Inactive</option>	
		<?php } ?>	
		</select>

		<div class="help-block"></div>
		</div>		

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


</div>
</div>
