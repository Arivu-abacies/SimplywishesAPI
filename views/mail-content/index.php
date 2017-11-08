<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchMailContent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mail Contents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-content-index">

    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a('Create Mail Content', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'m_id',
            //'mail_key',
            'mail_type',
            'mail_subject',
            'mail_message:ntext',
            // 'mail_variable:ntext',
            // 'status',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn','template' => '{view}{update}'],
        ],
    ]); ?>
</div>
