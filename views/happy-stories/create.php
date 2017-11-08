<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Editorial */

$this->title = 'Tell Your Story';
$this->params['breadcrumbs'][] = ['label' => 'Editorials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//Yii::$app->controller->renderPartial('account/_profilenew');
?>
<?php echo $this->render('@app/views/account/_profilenew',['user'=>$user,'profile'=>$profile])?>
<div class=" col-md-8 happy-stories-create">

    <h1 class="fnt-green" ><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
