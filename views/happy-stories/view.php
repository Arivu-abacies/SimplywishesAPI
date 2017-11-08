<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = "View Happy Stories";
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-view">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update-new', 'id' => $model->hs_id], ['class' => 'btn btn-primary']) ?>
        <?php /* = Html::a('Delete', ['delete', 'id' => $model->hs_id], [
            'class' => 'btn btn-danger ',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])  */?>
		
		<?= Html::submitButton('Delete', ['class' => 'btn btn-danger deletecheck','for'=>$model->hs_id ]) ?>
		
		 <?= Html::a('All Sotries', ['permission',], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [                   
			
			[
                'label'=>'Author Name',
				'value'=>$model->author->fullname,
            ],
			
            'wish.wish_title',			
            'story_text:ntext',          
			[
                'attribute'=>'Image',
				'value'=>!empty($model->story_image)?Yii::$app->homeUrl.$model->story_image:'',
				 'format' => !empty($model->story_image)?['image',['height'=>'100px']]:'text',
				
            ],
			[
                'label'=>'status',
				'value'=>!empty($model->status)?"Inactive":'Active',
            ],			
           
        ],
    ]) ?>

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
				window.location.href = "<?= Url::to('permission')?>"
			}
		});
		
	}); 
	
</script>
