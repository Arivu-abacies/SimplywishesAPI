<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-view">

    <h1 style="color:#99cc33"><?= Html::encode($this->title) ?></h1>
	<?php if(!Yii::$app->user->isGuest && \Yii::$app->user->identity->role == 'admin'){?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->p_id], ['class' => 'btn btn-primary']) ?>
        <?php /* echo Html::a('Delete', ['delete', 'id' => $model->p_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p>
	<?php } ?>

    <?= $model->content?>

</div>
