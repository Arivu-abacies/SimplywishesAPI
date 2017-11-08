<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Wish */

$this->title = 'Update Wish: ' . $model->wish_title;
$this->params['breadcrumbs'][] = ['label' => 'Wishes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->w_id, 'url' => ['view', 'id' => $model->w_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?php echo $this->render('@app/views/account/_profilenew',['user'=>$user,'profile'=>$profile])?>

<div class=" col-md-8 wish-update">

    <h1 class="fnt-green"  ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'categories' => $categories,
		'countries' => $countries,
		'states' => $states,
		'cities' => $cities
    ]) ?>

</div>
</div>
