<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\SearchWish */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wish-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'w_id') ?>

    <?= $form->field($model, 'wished_by') ?>

    <?= $form->field($model, 'granted_by') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'wish_title') ?>

    <?php // echo $form->field($model, 'summary_title') ?>

    <?php // echo $form->field($model, 'wish_description') ?>

    <?php // echo $form->field($model, 'primary_image') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'city') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
