<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SearchWish */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wishes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wish-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Wish', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'w_id',
            'wished_by',
            'granted_by',
            'category',
            'wish_title',
            // 'summary_title',
            // 'wish_description:ntext',
            // 'primary_image:ntext',
            // 'state',
            // 'country',
            // 'city',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
