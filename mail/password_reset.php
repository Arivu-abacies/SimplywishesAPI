<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<div class="password-reset">
<h2>Reset your password here</h2>
<?= Html::a('Reset', Url::to(['account/reset-password','key'=>$key],true)) ?>

</div>