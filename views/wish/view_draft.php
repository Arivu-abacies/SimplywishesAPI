<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = $model->wish_title;
$this->params['breadcrumbs'][] = $this->title;
$wishstatus = array('0'=>"Active",'1'=>"In-Active");
$payoption = array('0'=>"Financial",'1'=>"Non-financial",'2'=>"Decide Later");

?>

<div class="editorial-view">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update-draft', 'id' => $model->w_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete-draft', 'id' => $model->w_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
		
		 <?= Html::a('Back', ['my-drafts',], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [          
		 
		   [
				 'label'=>'Recipient',
				 'value' => $model->categoryName,
		  ],
		  
		  'wish_title',
			
			[
                'attribute'=>'primary_image',
				'value'=>!empty($model->primary_image)?Yii::$app->homeUrl.$model->primary_image:'',
				 'format' => !empty($model->primary_image)?['image',['height'=>'200px','width'=>'200px']]:'text',
				
            ],
			
			[
				 'label'=>'Country',
				 'value' =>($model->country != 0)?$model->countryModel->name:"-",
		  ],
		  [
				 'label'=>'State',
				 'value' =>($model->state != 0)?$model->stateModel->name:"-",
		  ],
		  [
				 'label'=>'City',
				 'value' =>($model->city != 0)?$model->cityModel->name:"-",
		  ],
		  
		   [
				 'label'=>'Expected cost',
				 'value' =>($model->non_pay_option == 1)?"-":$model->expected_cost,
				  'visible' => ($model->non_pay_option == 0)?true:false,
		  ],
		  
		  [
				 'label'=>'Address',
				 'value' =>($model->show_mail_status == 1)?$model->show_mail:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_mail_status == 1))?true:false,
		  ],
		  [
				 'label'=>'In Person Location',
				 'value' =>($model->show_person_status == 1)?$model->show_person_location:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_person_status == 1))?true:false,
		  ],
		  [
				 'label'=>'In Person Date',
				 'value' =>($model->show_person_status == 1)?$model->show_person_date:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_person_status == 1))?true:false,
		  ],
		  [
				 'label'=>'Reserved Under Full Name',
				 'value' =>($model->show_reserved_status == 1)?$model->show_reserved_name:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_reserved_status == 1))?true:false,
		  ],
		   [
				 'label'=>'Reserved Under Location',
				 'value' =>($model->show_reserved_status == 1)?$model->show_reserved_location:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_reserved_status  == 1))?true:false,
		  ],
		   [
				 'label'=>'Reserved Under Date',
				 'value' =>($model->show_reserved_status == 1)?$model->show_reserved_date:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_reserved_status == 1))?true:false,
		  ],
		  
		  [
				 'label'=>'Reserved Under Date',
				 'value' =>($model->show_other_status == 1)?$model->show_other_specify:'-',
				 'visible' => (($model->non_pay_option == 1) && ($model->show_other_status == 1))?true:false,
		  ],
		  
		  'expected_date',
		  'in_return',
		  'who_can',
		  
		  

		  [
				 'label'=>'Pay Option',
				 'value' =>$payoption[$model->non_pay_option],
		  ],

		  [
				 'label'=>'Status',
				 'value' =>$wishstatus[$model->wish_status],
		  ],
		  
        ],
    ]) ?>

</div>
