<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SearchEditorial */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Editorials';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-index">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Editorial', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'e_id',
            'e_title',
            'e_text:ntext',
            //'e_image',
			
			
           // 'status',
            // 'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
