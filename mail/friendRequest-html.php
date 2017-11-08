<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/friend-requested']);

?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p> <?= Html::encode(ucfirst($fromuser->firstname).' '.ucfirst($fromuser->lastname)) ?> has been send a friend request to you </p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
