<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SearchEditorial */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stories Approval';
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
				'attribute' => 'fullname',
				'label' => 'Author Name',
				'value' => 'author.fullname',	
				'enableSorting' => false,					
			],	
			
			[
				'attribute' => 'wishtitle',
				'label' => 'Wish Title',
				'value' => 'wish.wish_title',
				'enableSorting' => false,					
			],	
			 
			[
				'attribute' => 'story_text',
				'value' =>  'story_text',	
				'enableSorting' => false,				
			],			 
                    
          
			[
                'attribute'=>'status',
				'value' => function($model, $key, $index)
				{   
						if($model->status == '0')
						{
							return 'Active';
						}
						else
						{   
							return 'Inactive';
						}
				},
                'filter'=>Html::activeDropDownList($searchModel, 'status', array("0"=>"Active","1"=>"Inactive"),['class'=>'form-control','prompt' => '--Select Status--']),
				'enableSorting' => false,
            ],
			
	
			[
  'class' => 'yii\grid\ActionColumn',
  'template' => '{view}{update}{delete}',
  'buttons' => [
    'view' => function ($url, $model) {
        return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-eye-open"></span>', 'view?id='.$model->hs_id, [
                    'title' => Yii::t('app', 'View'),
        ]);
    },
	'update' => function ($url, $model) {
        return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-pencil"></span>', 'update-new?id='.$model->hs_id, [
                    'title' => Yii::t('app', 'Update'),
        ]);
    },
	'delete' => function ($url, $model) {
         return Html::a('<span style="margin-left:5px" class="glyphicon glyphicon-trash"></span>', '#', [
					'class' => 'deletecheck',
					'for'=>$model->hs_id, 
                    'title' => Yii::t('app', 'Delete'),					
        ]); 
		
    },
  ],
],


        ],
    ]); ?>
</div>

<script>

$(document).on('click', '.deletecheck', function(){ 
		var checkmsg = confirm("Are you sure you want to delete this item?");	
		if(checkmsg == false)
		{
			return false;
		}
		
		var id = $(this).attr('for');
		$.ajax({
			url : '<?=Url::to(['happy-stories/delete'])?>',
			type: 'POST',
			data: { id:id },
			success:function(data){
				location.reload();
			}
		});
		
	}); 
	
</script>

