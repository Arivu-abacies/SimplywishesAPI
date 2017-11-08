<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = $model->e_title;
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="editorial-view">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->e_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->e_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		
		 <?= Html::a('All Editorials', ['index',], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           
            'e_title',
            'e_text:ntext',
           // 'e_image',
			[
                'attribute'=>'Image',
				'value'=>!empty($model->e_image)?Yii::$app->homeUrl.$model->e_image:'',
				 'format' => !empty($model->e_image)?['image',['height'=>'100px']]:'text',
				
            ],	
			
			[
                'attribute'=>'Video',
				'value'=>!empty($model->featured_video_url)?$model->featured_video_url:'-',
				'format' => 'text',
				 
            ],
           
        ],
    ]) ?>

</div>
