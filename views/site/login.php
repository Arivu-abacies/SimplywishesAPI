<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>
<?php 
	$sessioncheck = Yii::$app->session->getFlash('success');
	if(isset($sessioncheck) && !empty($sessioncheck)) { ?>
	<div id="w3-success-0" class="alert-success alert fade in">
	<button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
	<?= Yii::$app->session->getFlash('success'); ?>
	</div>
<?php } ?>

 <?php if (Yii::$app->session->hasFlash('activeCheckmail')){ ?>

        <div class="alert alert-success">
            Your Validation is Complete, Please Login. 
        </div>

    <?php } else if (Yii::$app->session->hasFlash('RegisterFormSubmitted')){ ?>

        <div class="alert alert-success">
            Your Registration Has Been Successfully Completed,  Please Check Your E-mail for validation. 
        </div>

    <?php } else if(Yii::$app->session->hasFlash('RegisterEmailNot')) { ?>
		<div  class="alert-danger alert ">
			Oops, Sorry Your E-Mail Is Not validated, Please Check Your E-Mail 
		</div>
	<?php } ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

		<div class="form-group">
		
		<div class="col-lg-1"></div>
		<div class="col-lg-8">
		<div style="color:#999;">
                    Forgotten Password? reset it <?= Html::a('here', ['site/request-password-reset']) ?>.
         </div>
		</div>
		</div>
		 
				
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
