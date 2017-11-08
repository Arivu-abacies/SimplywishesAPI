<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Update: ' . $model->e_title;
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->e_id, 'url' => ['view', 'id' => $model->e_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="editorial-update">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
