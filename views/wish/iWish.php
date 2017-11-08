<?php 
use yii\helpers\Url;
use app\models\UserProfile;
?>
<div class="smp-igrant">
<h3 class="smp-mg-bottom fnt-green">Our Wishers</h3>
	<div class="col-md-12 smp-mg-bottom">
		<?php foreach($dataProvider->models as $model){
			$userProfile = UserProfile::find()->where(['user_id'=>$model->wished_by])->one();
			echo '<a href="'.Url::to(['account/profile','id'=>$userProfile->user_id]).'"><img src="'.\Yii::$app->homeUrl.$userProfile->profile_image.'" width="100px" height="100px"/></a>';
		}?>
	</div>
</div>