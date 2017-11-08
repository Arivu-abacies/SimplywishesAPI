<?php

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;
use app\models\Customer;
use app\models\Country;
use yii\filters\VerbFilter;

class MobileController extends ActiveController
{
  
    //public $modelClass = '';
	//public $modelClass = 'app\models\Country';
	public $modelClass = 'app\models\User';
	
	
	public function actionCountryList()
	{
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
		$result = json_encode($countries);
		return $result;
	}
	
	public function actionStateList()
	{
		$countryid = $_POST['countryid'];
		$states = \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(["country_id"=>$countryid])->all(),'id','name');	
		$result = json_encode($states);
		return $result;
	}
	
	public function actionCityList($stateid)
	{
		$city = \yii\helpers\ArrayHelper::map(\app\models\City::find()->where(["state_id"=>$stateid])->all(),'id','name');	
		$result = json_encode($city);
		return $result;
	}
	
}


/* 
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;
use app\models\UserProfile;
use yii\web\UploadedFile;
use app\models\search\SearchWish;
use yii\data\ActiveDataProvider;
use app\models\Wish;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\Page;

use ZipArchive;

class MobileController extends Controller
{
   
	public function actionCountryList()
	{
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
		$result = json_encode($countries);
		return $result;
	}
	
	public function actionStateList($countryid)
	{
		$states = \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(["country_id"=>$countryid])->all(),'id','name');	
		$result = json_encode($states);
		return $result;
	}
	
	public function actionCityList($stateid)
	{
		$city = \yii\helpers\ArrayHelper::map(\app\models\City::find()->where(["state_id"=>$stateid])->all(),'id','name');	
		$result = json_encode($city);
		return $result;
	}
}
 */