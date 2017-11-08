<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SearchEditorial */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Report Action';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-index">

    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'wishtitle',
				'label' => 'Wish title',
				'format' => 'raw',			
			  'value'=>function ($data) {
						 return Html::a($data->wish->wish_title, 'report-action-view?id='.$data->wish->w_id, [
						'target' => '_blank',
						]); 
					},					  
			],	
			
			[
				'attribute' => 'count',
				'label' => 'Count',
				'value' => 'count',							
			],	
			
        ],
    ]); ?>
</div>



