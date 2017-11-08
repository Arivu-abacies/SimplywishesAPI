<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchMailContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mail-content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'm_id') ?>

    <?= $form->field($model, 'mail_key') ?>

    <?= $form->field($model, 'mail_type') ?>

    <?= $form->field($model, 'mail_subject') ?>

    <?= $form->field($model, 'mail_message') ?>

    <?php // echo $form->field($model, 'mail_variable') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
