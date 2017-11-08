<?php 

namespace app\modules\api\controllers;

use yii;
use yii\rest\ActiveController;
use yii\web\UploadedFile;
use app\models\LoginForm;
use app\models\UserProfile;
use app\models\User;
use app\models\Country;
use app\models\State;
use app\models\City;
use app\models\MailContent;
use app\models\Wish;
use app\models\Activity;
use app\models\search\SearchWish;
use yii\data\ActiveDataProvider;

/**
* Default controller for the `v1` module
*/
class WishController extends ActiveController
{
   public $modelClass = 'app\models\User';
   /**
    * Renders the index view for the module
    * @return string
    */
   public function actionApi()
   {

       $countries2 = Yii::$app->request->post('action');
       

       if(method_exists($this,$countries2)){
           return $this->$countries2();
       }else{
			return json_encode(['result'=>'failed','message'=>'Wrong action name']);
       }

       //return $this->$countries2();
       /* try{
           return $this->$countries2();
       }
       catch(\Exception as $e){
           return 'Function does not exist'
       } */
       
       
   }
   
   function Login(){
       $model = new LoginForm();
       $model->username = Yii::$app->request->post('username');
       $model->password = Yii::$app->request->post('password');
       if ($model->login()) {
           $userid = Yii::$app->user->id;
		   $user_details = UserProfile::find()->where(['user_id'=>$userid])->asArray()->one();		   
           return json_encode(['result'=>'success','userid'=>$userid,'user_details'=>$user_details]);
       }else{
           return json_encode(['result'=>'failed','message'=>$model->errors]);
       }
   }
   
     function Register(){
        $user = new User();
		$user->scenario = 'sign-up';
		$profile = new UserProfile();
		$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->all(),'id','name');	
	
		$privacy_policy = \app\models\Page::find()->where(['p_id'=>1])->one();		
		$terms = \app\models\Page::find()->where(['p_id'=>2])->one();		
		$community_guidelines = \app\models\Page::find()->where(['p_id'=>3])->one();
		
		$user->username         = Yii::$app->request->post('username');
		$user->email            = Yii::$app->request->post('email');
		$user->password         = Yii::$app->request->post('password');
		$user->verify_password  = Yii::$app->request->post('verify_password');
        
		$profile->firstname     = Yii::$app->request->post('firstname');
		$profile->lastname      = Yii::$app->request->post('lastname');
		$profile->country       = Yii::$app->request->post('country');
		$profile->state         = Yii::$app->request->post('state');
		$profile->city          = Yii::$app->request->post('city');
		$profile->about         = Yii::$app->request->post('about');
		$profile->dulpicate_image= file_get_contents(Yii::$app->request->post('dulpicate_image'));
        $profile->profile_image = 'uploads/newimg'.date('Y-m-d_H-m-s').'.png';
        copy(Yii::$app->request->post('profile_image'),$profile->profile_image);

        $user->setPassword($user->password);
        $user->generateAuthKey();
        $user->status = 10;
        // In-Active State 
        if($user->save()){
            $profile->user_id = $user->id;
            if(!$profile->save()){
                return json_encode(['result'=>'failed','message'=>$profile->errors]);
            }
            $profile->sendVAlidationEmail($user->email);
            return json_encode(['result'=>'success','userid'=>$user->id]);
        } 
        else 
        {
            return json_encode(['result'=>'failed','message'=>$user->errors]);
        }
    }
    
    function WishedFor(){
        $wish_id    = Yii::$app->request->post('wish_id');
        $type       = Yii::$app->request->post('type');
        $wish = Wish::find()->where(['w_id'=>$wish_id])->one();
		$activity = Activity::find()->where(['wish_id'=>$wish->w_id,'activity'=>$type,'user_id'=>\Yii::$app->user->id])->one();
		if($activity != null){
			$activity->delete();
            $message = "removed";
			return json_encode(['result'=>'success','message'=>$message]);
		}
        else{
            $activity = new Activity();
            $activity->wish_id = $wish->w_id;
            $activity->activity = $type;
            $activity->user_id = \Yii::$app->user->id;
            if($activity->save()){
                $message = "added";
                return json_encode(['result'=>'success','message'=>$message]);
            }
            else{
                return json_encode(['result'=>'failed','message'=>$user->errors]);
            }
        }
    }
	
   function Countrylist()
   {
       	$countries = \yii\helpers\ArrayHelper::map(\app\models\Country::find()->asArray()->all(),'id','name');	
       	if($countries)
			return json_encode(['result'=>'success','countries'=>$countries]);
		else 
			return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);
   }
   
   
   function Countryname()
   {
	    $countryid = Yii::$app->request->post('countryid');
       	$countries = \app\models\Country::find()->where(["id"=>$countryid])->asArray()->One();	
       	if($countries)
			return json_encode(['result'=>'success','countries'=>$countries]);
		else 
			return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);
   }
   
   function Statelist()
   {
	    $countryid = Yii::$app->request->post('countryid');
		if($countryid)
		{
			$states = \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(['country_id'=>$countryid])->asArray()->all(),'id','name');	
			if($states)
				return json_encode(['result'=>'success','states'=>$states]);
			else 
				return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);
		}
		else
		{		
			return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);	
		}
		
   }
   
   function Statename()
   {
	    $stateid = Yii::$app->request->post('stateid');
       	$states = \app\models\State::find()->where(["id"=>$stateid])->asArray()->One();	
       	if($states)
			return json_encode(['result'=>'success','states'=>$states]);
		else 
			return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);
   }
   
   function Citylist()
   {
	    $stateid = Yii::$app->request->post('stateid');
		if($stateid)
		{
			$cities = \yii\helpers\ArrayHelper::map(\app\models\City::find()->where(['state_id'=>$stateid])->asArray()->all(),'id','name');	
			if($cities)
				return json_encode(['result'=>'success','cities'=>$cities]);
			else 
				return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);
		}
		else
		{		
			return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);	
		}		
   }
   
   
   function Cityname()
   {
	    $cityid = Yii::$app->request->post('cityid');
       	$city = \app\models\City::find()->where(["id"=>$cityid])->asArray()->One();	
       	if($city)
			return json_encode(['result'=>'success','city'=>$city]);
		else 
			return json_encode(['result'=>'failed','message'=>"Invaild Exception"]);
   }
   
   
   function Userdefaultimage()
   {
	   $image = array(	"images/img1.jpg","images/img2.jpg","images/img3.jpg","images/img4.jpg",
						"images/img5.jpg","images/img6.jpg","images/img7.jpg","images/img8.jpg",
						"images/img9.jpg","images/img10.jpg","images/img11.jpg","images/img12.jpg",
						"images/img13.jpg","images/img14.jpg","images/img15.jpg","images/img16.jpg",
						"images/img17.jpg","images/img18.jpg","images/img19.jpg","images/img20.jpg",
						"images/img21.jpg","images/img22.jpg","images/img23.jpg","images/img24.jpg",
						"images/img25.jpg","images/img26.jpg","images/img27.jpg","images/img28.jpg",
						"images/img29.jpg","images/img30.jpg","images/img31.jpg","images/img32.jpg",
						"images/img33.jpg","images/img34.jpg","images/img35.jpg","images/img36.jpg",						
						);	
	   return json_encode(['result'=>'success','image'=>$image]);
   }
   
   function HomeFullfilledWishes()
   {
	   $userid = Yii::$app->request->post('userid');
	   
	   $query = Wish::find()->where(['not', ['granted_by' => null]])->orderBy('w_id DESC');	
       $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'pagination' => [
                'pageSize'=>12
            ] 
        ]);
			
		$models = [];
		$i = 0;
		if($dataProvider->models)
		{
			foreach($dataProvider->models as $model)
			{
				$models[$i]['w_id'] = $model->w_id;
				$models[$i]['wish_title'] = $model->wish_title;
				$models[$i]['primary_image'] = $model->primary_image;
				$models[$i]['wisherPic'] = $model->wisherPic;
				$models[$i]['wished_by'] = $model->wished_by;
				$models[$i]['wisherName'] = $model->wisherName;
				$models[$i]['location'] = $model->location;
				$models[$i]['likesCount'] = $model->likesCount;
				
				if($userid)
					$models[$i]['isFaved'] = $model->isFaved($userid);
				else 
					$models[$i]['isFaved'] = 0;
				
				if($userid)
					$models[$i]['isLiked'] = $model->isLiked($userid);
				else 
					$models[$i]['isLiked'] = 0;
				
				$i++;
			}
			
		   return json_encode(['result'=>'success','models'=>$models]);
		} else {
			return json_encode(['result'=>'failed','message'=>"Invalid Exception"]);
		}
   }
   
   function GrantedFullfilledWishes()
   {
	   $userid = Yii::$app->request->post('userid');
	   $searchModel = new SearchWish();
       $dataProvider = $searchModel->searchGranted(Yii::$app->request->queryParams);
			
		$models = [];
		$i = 0;
		if($dataProvider->models)
		{
			foreach($dataProvider->models as $model)
			{
				$models[$i]['w_id'] = $model->w_id;
				$models[$i]['wish_title'] = $model->wish_title;
				$models[$i]['primary_image'] = $model->primary_image;
				$models[$i]['wisherPic'] = $model->wisherPic;
				$models[$i]['wished_by'] = $model->wished_by;
				$models[$i]['wisherName'] = $model->wisherName;
				$models[$i]['location'] = $model->location;
				$models[$i]['likesCount'] = $model->likesCount;
				
				if($userid)
					$models[$i]['isFaved'] = $model->isFaved($userid);
				else 
					$models[$i]['isFaved'] = 0;
				
				if($userid)
					$models[$i]['isLiked'] = $model->isLiked($userid);
				else 
					$models[$i]['isLiked'] = 0;
				
				$i++;
			}
			
		   return json_encode(['result'=>'success','models'=>$models]);
		} else {
			return json_encode(['result'=>'failed','message'=>"Invalid Exception"]);
		}
   }
   
   
    function ScrolledGrantedFullfilledWishes()
   {
	   $userid = Yii::$app->request->post('userid');
	   $page = Yii::$app->request->post('page');
	   
		$searchModel = new SearchWish();
        $dataProvider = $searchModel->searchGranted(Yii::$app->request->queryParams);
		$dataProvider->pagination->page = $page;
			
		$models = [];
		$i = 0;
		if($dataProvider->models)
		{
			foreach($dataProvider->models as $model)
			{
				$models[$i]['w_id'] = $model->w_id;
				$models[$i]['wish_title'] = $model->wish_title;
				$models[$i]['primary_image'] = $model->primary_image;
				$models[$i]['wisherPic'] = $model->wisherPic;
				$models[$i]['wished_by'] = $model->wished_by;
				$models[$i]['wisherName'] = $model->wisherName;
				$models[$i]['location'] = $model->location;
				$models[$i]['likesCount'] = $model->likesCount;
				
				if($userid)
					$models[$i]['isFaved'] = $model->isFaved($userid);
				else 
					$models[$i]['isFaved'] = 0;
				
				if($userid)
					$models[$i]['isLiked'] = $model->isLiked($userid);
				else 
					$models[$i]['isLiked'] = 0;
				
				$i++;
			}
			
		   return json_encode(['result'=>'success','models'=>$models]);
		} else {
			return json_encode(['result'=>'failed','message'=>"Invalid Exception"]);
		}
   }
   
   function ViewWishes()
   {
	   $id = Yii::$app->request->post('w_id');
	   $userid = Yii::$app->request->post('userid');

	   $model = Wish::find()->where(['w_id'=>$id])->one();	
	  
	   $models = [];
	  
	   if($model)
		{
			
				$models['w_id'] = $model->w_id;
				$models['wish_title'] = $model->wish_title;
				$models['primary_image'] = $model->primary_image;
				$models['wisherPic'] = $model->wisherPic;
				$models['wished_by'] = $model->wished_by;
				$models['wisherName'] = $model->wisherName;
				$models['location'] = $model->location;
				$models['likesCount'] = $model->likesCount;
				$models['wish_description'] = $model->wish_description;
				$models['expected_date'] = $model->expected_date;
				$models['in_return'] = $model->in_return;
				$models['who_can'] = $model->who_can;
				$models['categoryName'] = $model->categoryName;
				$models['granted_by'] = $model->granted_by;
				$models['non_pay_option'] = $model->non_pay_option;
				$models['wisheremail'] = $model->wisher->email;
				$models['expected_cost'] = $model->expected_cost;
				$models['process_granted_by'] = $model->process_granted_by;
				$models['process_status'] = $model->process_status;
				$models['process_granted_date'] = $model->process_granted_date;
				$models['show_mail'] = $model->show_mail;
				$models['show_mail_status'] = $model->show_mail_status;
				$models['show_person_status'] = $model->show_person_status;
				$models['show_person_date'] = $model->show_person_date;
				$models['show_reserved_status'] = $model->show_reserved_status;
				$models['show_reserved_name'] = $model->show_reserved_name;
				$models['show_reserved_location'] = $model->show_reserved_location;
				$models['show_other_status'] = $model->show_other_status;
				$models['show_other_specify'] = $model->show_other_specify;
				
				
				if($userid)
					$models['isFaved'] = $model->isFaved($userid);
				else 
					$models['isFaved'] = 0;
				
				if($userid)
					$models['isLiked'] = $model->isLiked($userid);
				else 
					$models['isLiked'] = 0;
				
			
			
		   return json_encode(['result'=>'success','models'=>$models]);
		} else {
			return json_encode(['result'=>'failed','message'=>"Invalid Exception"]);
		}
		
   }
   
}


?>