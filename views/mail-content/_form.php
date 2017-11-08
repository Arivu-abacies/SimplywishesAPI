<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\MailContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'mail_key')->textInput(['maxlength' => true]) ?>
	
    <?= $form->field($model, 'mail_type')->textInput(['maxlength' => true,'readonly'=>'readonly']) ?>

    <?= $form->field($model, 'mail_subject')->textInput(['maxlength' => true]) ?>



	<?= $form->field($model, 'mail_message')->widget(CKEditor::className(), [
        'options' => ['rows' => 8],
        'preset' => 'basic'
    ]); ?>
	
    <?= $form->field($model, 'mail_variable')->textarea(['rows' => 3 ,'readonly'=>'readonly']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		
		<?= Html::a('Back', ['index'], ['class' => 'btn btn-success']) ?>
		 
    </div>

    <?php ActiveForm::end(); ?>

</div>
