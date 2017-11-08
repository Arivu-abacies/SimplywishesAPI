<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Create Editorial';
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-create">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
