<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MailContent */

$this->title = 'Update : ' . $model->mail_type;
$this->params['breadcrumbs'][] = ['label' => 'Mail Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->m_id, 'url' => ['view', 'id' => $model->m_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mail-content-update">

    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
