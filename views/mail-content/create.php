<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MailContent */

$this->title = 'Create Mail Content';
$this->params['breadcrumbs'][] = ['label' => 'Mail Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-content-create">

    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
