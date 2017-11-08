<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MailContent */

$this->title = $model->mail_type;
$this->params['breadcrumbs'][] = ['label' => 'Mail Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-content-view">

    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->m_id], ['class' => 'btn btn-primary']) ?>
		
		 <?= Html::a('Back', ['index'], ['class' => 'btn btn-success']) ?>
		 
        <?php /* Html::a('Delete', ['delete', 'id' => $model->m_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          //  'm_id',
          //  'mail_key',
            'mail_type',
            'mail_subject',
            'mail_message:ntext',
            'mail_variable:ntext',
           // 'status',
           // 'created_at',
        ],
    ]) ?>

</div>
