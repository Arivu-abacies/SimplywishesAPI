<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "wishes".
 *
 * @property integer $w_id
 * @property integer $wished_by
 * @property integer $granted_by
 * @property integer $category
 * @property string $wish_title
 * @property string $summary_title
 * @property string $wish_description
 * @property string $primary_image
 * @property integer $state
 * @property integer $country
 * @property integer $city
 */
class Wish extends \yii\db\ActiveRecord
{
	public $auto_id;
	public $primary_image_name;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wishes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['category', 'wish_title','state', 'country', 'city','expected_cost','expected_date'], 'required'],
            [['category', 'wish_title','state', 'country', 'city','expected_date','non_pay_option'], 'required'],
			
            [['non_pay_option'], 'required','when' => function($model) { return $model->non_pay_option == 0; }],
			
			//['primary_image', 'required', 'message' => '{attribute} can\'t be blank', 'on'=>'create'],
            [['wished_by', 'granted_by', 'category', 'state', 'country', 'city'], 'integer'],
            [['wish_description'], 'string'],
			[['primary_image'], 'file','extensions' => 'jpg, jpeg, png, bmp, gif, tif, tiff', 'skipOnEmpty' => true],
            [['wish_title'], 'string', 'max' => 100],
            [['summary_title','who_can'], 'string', 'max' => 150],
			[['in_return'], 'string', 'max' => 1500],
			[['expected_cost'], 'double'],
			[['non_pay_option','i_agree_decide2','i_agree_decide'], 'integer'],
			[['auto_id','wish_status','primary_image_name'], 'safe'],
		//	[['expected_cost'], 'in','range'=>range(100,1000),'message'=>'Expected Cost(USD) Range In 100 to 1000' ],
			[['show_mail_status','show_person_status','show_reserved_status','show_other_status'], 'integer'],
			[['show_person_location','show_person_date','show_reserved_name','show_reserved_location','show_reserved_date','show_other_specify'], 'string'],
			['show_mail','email','message' => 'Enter valid email address'],
			
        ];
    }
	


	public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['category', 'wish_title','summary_title', 'wish_description','primary_image','state', 'country', 'city','expected_cost','expected_date','in_return','who_can','non_pay_option','auto_id','wish_status','show_mail_status','show_person_status','show_reserved_status','show_other_status','show_mail','show_person_location','show_person_date','show_reserved_name','show_reserved_location','show_reserved_date','show_other_specify','i_agree_decide2','i_agree_decide','primary_image_name'];
		
		 $scenarios['update'] = ['category', 'wish_title','summary_title', 'wish_description','state', 'country', 'city','expected_cost','expected_date','in_return','who_can','non_pay_option','auto_id','wish_status','show_mail_status','show_person_status','show_reserved_status','show_other_status','show_mail','show_person_location','show_person_date','show_reserved_name','show_reserved_location','show_reserved_date','show_other_specify','i_agree_decide2','i_agree_decide','primary_image_name'];
		 
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'w_id' => 'W ID',
            'wished_by' => 'Wished By',
            'granted_by' => 'Granted By',
            'category' => 'Recipient',
            'wish_title' => 'Wish Title',
            'summary_title' => 'Wish Summary',
            'wish_description' => 'Wish Description',
            'primary_image' => 'Primary Image',
            'state' => 'State',
            'country' => 'Country',
            'city' => 'City',
			 'expected_cost'=>'Expected Cost(USD)', 
			
			'expected_date'=>'Issue Date',
			'non_pay_option'=>'Wishes Type ',			
			'in_return'=>'What Do I Give In Return',
			'who_can'=>'Who Can Potentialy Help me',
			
			
			'show_mail_status'=>'Mail',
			'show_person_status'=>'In Person',
			'show_reserved_status'=>'Reserved Under Your Name',
			'show_other_status'=>'Other',
			'show_mail'=>'Address ( not made public )',
			'show_person_location'=>'Location',
			'show_person_date'=>'Date',
			'show_reserved_name'=>'Full Name',
			'show_reserved_location'=>'Location',
			'show_reserved_date'=>'Date',
			'show_other_specify'=>'Please Specify',
        ];
    }
	public function uploadImage(){
		//if($this->validate()) {
			$this->primary_image->saveAs('uploads/' .time().$this->primary_image->baseName. '.' .$this->primary_image->extension);
			$this->primary_image = 'uploads/'.time().$this->primary_image->baseName.'.'.$this->primary_image->extension;
			return true;
		//}else
		//	return false;
	}
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWisher()
    {
        return $this->hasOne(User::className(), ['id' => 'wished_by']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryModel()
    {
        return $this->hasOne(Country::className(), ['id' => 'country']);
    }	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStateModel()
    {
        return $this->hasOne(State::className(), ['id' => 'state']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCityModel()
    {
        return $this->hasOne(City::className(), ['id' => 'city']);
    }	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Activity::className(), ['wish_id' => 'w_id']) ->andOnCondition(['activity' => 'like']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaved()
    {
        return $this->hasMany(Activity::className(), ['wish_id' => 'w_id']) ->andOnCondition(['activity' => 'fav']);
    }	
    /**
     * @return no of likes
     */
    public function getLikesCount()
    {
        return Activity::find()->where(['wish_id'=>$this->w_id,'activity'=>'like'])->count();
    }	
	
    /**
	 * Is this wish liked by the particular user
     * @return boolean
     */	
	public function isLiked($byUser){
		if(Activity::find()->where(['wish_id'=>$this->w_id,'activity'=>'like','user_id'=>$byUser])->one()!= null)
			return true;
		else return false;
	}
	
    /**
	 * Is this wish favourited by the particular user
     * @return boolean
     */		
	public function isFaved($byUser){
		if(Activity::find()->where(['wish_id'=>$this->w_id,'activity'=>'fav','user_id'=>$byUser])->one()!= null)
			return true;
		else return false;
	}
	
    /**
	 * Forms th html for a particular wish
     * @return html
     */		
	public function getWishAsCard(){
		  $str = '';
          $str .= '<div class="grid-item col-md-4"><div class=" smpl-wish-block1 thumbnail">';
          $str .= '<div><a href="'.Url::to(['wish/view','id'=>$this->w_id]).'"><img src="'.\Yii::$app->homeUrl.$this->primary_image.'" class="img-responsive" alt="Image"></a></div>';
          /////activities///
          if(!$this->isFaved(\Yii::$app->user->id))
            $str .=  '<div class="smp-links sharefull-list"><span title="Save this wish" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-orange"></span></br>';
          else
            $str .=  '<div class="smp-links sharefull-list"><span title="You saved it" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-blue"></span></br>';

          if(!$this->isLiked(\Yii::$app->user->id))
            $str .=  '<span title="Like it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-green"></span>';
          else
            $str .=  '<span title="You liked it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-pink"></span>';
          //////////////////
		  
		 $str .= '<br><span  data-placement="right"  data-popover-content=""><img data-placement="right" class="listesinside"  src="'.\Yii::$app->homeUrl.'images/Share-Icon.png"  /></span>
		  <div class="shareIcons hide" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>
		  </div>';
		  
          $str .=  '<div class="smp-wish-desc">';
            $str .=  '<p><div class="list-icon">
							<img src="'.$this->wisherPic.'" alt="">
							<a href="'.Url::to(['account/profile','id'=>$this->wished_by]).'"><span>'.$this->wisherName.'</span></a>
						</div></p>
            <!--<p>Wish For : <span>'.$this->wish_title.'</span></p>
            <p>Location : <span>'.$this->location.'</span></p>-->
			<p class="desc" >'.substr($this->wish_title,0,50).'</p>
            <p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$this->w_id]).'">Read More</a>
            &nbsp;<span><i class="fa fa-thumbs-o-up fa-1x fnt-blue"></i> <span id="likecmt_'.$this->w_id.'"  >'.$this->likesCount.'</span> Likes </span>
			 </p>';
          $str .=  '</div>';
          $str .=  '</div></div>';	
			
		echo $str;
	}
	public function getHtmlForProfile(){
		
		$str = "";
		
		
			 if(!$this->isFaved(\Yii::$app->user->id))
            $str .=  '<div class="smp-links sharefull-list"><span title="Save this wish" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-orange"></span></br>';
          else
            $str .=  '<div class="smp-links sharefull-list"><span title="You saved it" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-blue"></span></br>';

          if(!$this->isLiked(\Yii::$app->user->id))
            $str .=  '<span title="Like it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-green"></span>';
          else
            $str .=  '<span title="You liked it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-pink"></span>';
		
		
		    $str .= '<br><span  data-placement="right"  data-popover-content=""><img data-placement="right" class="listesinside"  src="'.\Yii::$app->homeUrl.'images/Share-Icon.png"  /></span>
		  <div class="shareIcons hide" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>
		  </div>';
		  
			$str .=  '<div class="smp-wish-desc">';
            $str .=  '<p><div class="list-icon">
							<img src="'.$this->wisherPic.'" alt="">
							<a href="'.Url::to(['account/profile','id'=>$this->wished_by]).'"><span>'.$this->wisherName.'</span></a>
							<p></p><p class="desc" >'.substr($this->wish_title,0,50).'</p>
						<p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$this->w_id]).'">Read More</a>
						&nbsp;<i class="fa fa-thumbs-o-up fa-1x fnt-blue"></i> <span id="likecmt_'.$this->w_id.'"  >'.$this->likesCount.'</span> Likes
						 </p>
						</div></p>';
          // $str .=  '<div class="shareIcons" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>';
		   
          $str .=  '</div>';
		  
		echo '<div class="grid-item col-md-4 "> 
				<div class="smpl-wish-block1 thumbnail">	
					<div>				
					<a href="'.Url::to(['wish/view','id'=>$this->w_id]).'"><img src="'.\Yii::$app->homeUrl.$this->primary_image.'"  class="img-responsive" alt="Image"></a></div>
				
					'.$str.'
				
				</div>
			</div>';
	}
	
	
		public function getHtmlForProfileOther(){
		
		$str = "";
		
		
			 if(!$this->isFaved(\Yii::$app->user->id))
            $str .=  '<div class="smp-links sharefull-list"><span title="Save this wish" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-orange"></span></br>';
          else
            $str .=  '<div class="smp-links sharefull-list"><span title="You saved it" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-blue"></span></br>';

          if(!$this->isLiked(\Yii::$app->user->id))
            $str .=  '<span title="Like it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-green"></span>';
          else
            $str .=  '<span title="You liked it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-pink"></span>';
		
		 $str .= '<br><span  data-placement="right"  data-popover-content=""><img data-placement="right" class="listesinside"  src="'.\Yii::$app->homeUrl.'images/Share-Icon.png"  /></span>
		  <div class="shareIcons hide" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>
		  </div>';
		  
		
			$str .=  '<div class="smp-wish-desc">';
            $str .=  '<p><div class="list-icon">
							<img src="'.$this->wisherPic.'" alt="">
							<a href="'.Url::to(['account/profile','id'=>$this->wished_by]).'"><span>'.$this->wisherName.'</span></a>
							<p></p><p class="desc" >'.substr($this->wish_title,0,50).'</p>
						<p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$this->w_id]).'">Read More</a>
						&nbsp;<i class="fa fa-thumbs-o-up fa-1x fnt-blue"></i> <span id="likecmt_'.$this->w_id.'"  >'.$this->likesCount.'</span> Likes
						 </p>
						</div></p>';
						
         //  $str .=  '<div class="shareIcons" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>';
		   
          $str .=  '</div>';
		  
		echo '<div class="grid-item col-md-3 "> 
				<div class="smpl-wish-block1 thumbnail">	
					<div>				
					<a href="'.Url::to(['wish/view','id'=>$this->w_id]).'"><img src="'.\Yii::$app->homeUrl.$this->primary_image.'"  class="img-responsive" alt="Image"></a></div>
				
				'.$str.'
				
				</div>
			</div>';
	}
	
	/*    old Formate data displayed 
	
		public function getHtmlForProfile(){
		echo '<div class="col-md-6 grid-item"> 
				<div class="smp_inline thumbnail wishimgsize">					
					<a href="'.Url::to(['wish/view','id'=>$this->w_id]).'"><img src="'.\Yii::$app->homeUrl.$this->primary_image.'"  class="img-responsive" alt="Image"></a> 	
							
				</div>
				
				<div class="smp_inline">
					<p><b>Wish Title : </b><span>'.$this->wish_title.'</span></p>
					<p><b>Wish Description : </b><span>'.substr($this->wish_description,0,25).'..</span></p>
					<p><b>Location : </b><span>'.$this->location.'</span></p>
					<p><b>Recipient : </b><span>'.$this->categoryName.'</span></p>
					<p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$this->w_id]).'">Read More >></a> </p>
					
					
				</div>
			</div>';
	}
	 */
	
		public function getHtmlForProfileSaved(){
			
			$str = "";
			
			 if(!$this->isFaved(\Yii::$app->user->id))
            $str .=  '<div class="smp-links sharefull-list"><span title="Save this wish" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-orange"></span></br>';
          else
            $str .=  '<div class="smp-links sharefull-list"><span title="You saved it" data-w_id="'.$this->w_id.'" data-a_type="fav" class="fav-wish fa fa-save txt-smp-blue"></span></br>';

          if(!$this->isLiked(\Yii::$app->user->id))
            $str .=  '<span title="Like it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-green"></span>';
          else
            $str .=  '<span title="You liked it" data-w_id="'.$this->w_id.'" data-a_type="like" class="like-wish glyphicon glyphicon glyphicon-thumbs-up txt-smp-pink"></span>';
		
		$str .= '<br><span  data-placement="right"  data-popover-content=""><img data-placement="right" class="listesinside"  src="'.\Yii::$app->homeUrl.'images/Share-Icon.png"  /></span>
		  <div class="shareIcons hide" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>
		  </div>';
		  
			$str .=  '<div class="smp-wish-desc">';
            $str .=  '<p><div class="list-icon">
							<img src="'.$this->wisherPic.'" alt="">
							<a href="'.Url::to(['account/profile','id'=>$this->wished_by]).'"><span>'.$this->wisherName.'</span></a>
							<p></p><p class="desc" >'.substr($this->wish_title,0,50).'</p>
						<p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$this->w_id]).'">Read More</a>
						&nbsp;<i class="fa fa-thumbs-o-up fa-1x fnt-blue"></i> <span id="likecmt_'.$this->w_id.'"  >'.$this->likesCount.'</span> Likes
						 </p>
						</div></p>';
			// $str .=  '<div class="shareIcons" data_text="'.$this->wish_title.'" data_url="'.Url::to(['wish/view','id'=>$this->w_id],true).'" ></div>';
			 
          $str .=  '</div>';
		  
		echo '<div class="grid-item col-md-4"> 
					
				<div class="smpl-wish-block1 thumbnail">	
					<div>
					<a href="'.Url::to(['wish/view','id'=>$this->w_id]).'"><img src="'.\Yii::$app->homeUrl.$this->primary_image.'"  class="img-responsive" alt="Image"></a> </div>
					'.$str.'
					

					<p><a style="margin-left:10px" class="fnt-danger" href="'.Url::to(['wish/remove-wish','wish_id'=>$this->w_id]).'"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a> </p>
				</div>				
			</div>';
	}
	
	
	/*   Old Formate 
		public function getHtmlForProfileSaved(){
			
			$str = "";
			$str .=  '<div class="smp-wish-desc">';
            $str .=  '<p><div class="list-icon">
							<img src="'.$this->wisherPic.'" alt="">
							<a href="'.Url::to(['account/profile','id'=>$this->wished_by]).'"><span>'.$this->wisherName.'</span></a>
							<p class="desc">'.substr($this->summary_title,0,50).'..</p>		
						</div></p>';
          
          $str .=  '</div>';
		  
		echo '<div class="col-md-6 grid-item"> 
				<div class="smp_inline thumbnail wishimgsize">					
					<a href="'.Url::to(['wish/view','id'=>$this->w_id]).'"><img src="'.\Yii::$app->homeUrl.$this->primary_image.'"  class="img-responsive" alt="Image"></a>
					<br>'.$str.'
					

					<p><a style="margin-left:10px" class="fnt-danger" href="'.Url::to(['wish/remove-wish','wish_id'=>$this->w_id]).'"><i class="fa fa-trash" aria-hidden="true"></i> Remove</a> </p>
				</div>
				
				<div class="smp_inline">
					<p><b>Wish Title : </b><span>'.$this->wish_title.'</span></p>
					<p><b>Wish Description : </b><span>'.substr($this->wish_description,0,25).'..</span></p>
					<p><b>Location : </b><span>'.$this->location.'</span></p>
					<p><b>Recipient : </b><span>'.$this->categoryName.'</span></p>
					<p><a class="fnt-green" href="'.Url::to(['wish/view','id'=>$this->w_id]).'">Read More >></a> </p>
					
					
				</div>
			</div>';
	}
	 */
	
    /**
     * @returns the location of the wish
     */	
	public function getLocation(){
		
		$country = Country::findOne($this->country);
		$state = State::findOne($this->state);
		$city = City::findOne($this->city);
		if(!$country && !$state && !$city)
			return "Unknown";
		
		
		$location  = "";
		
		if(!empty($city))
			$location  .= $city->name;
		if(!empty($state) && !empty($city))
			$location  .= ", ".$state->name;
		else if(!empty($state))
			$location  .= $state->name;
		
		if((!empty($state) || !empty($city)) && !empty($country))
			$location  .= ", ".$country->name;
		else if(!empty($country))
			$location  .= $country->name; 
		
		return "$location";
		
		//else return "$state->name , $country->name";
	}
    /**
     * @returns the location of the wish
     */	
	public function getCategoryName(){
		
		$Category = Category::findOne($this->category);
		if(!$Category)
			return "Unknown";
		else return "$Category->title";
	}
    /**
     * @returns the name of the wisher
     */	
	public function getWisherName(){
		
		$profile = UserProfile::find()->where(['user_id'=>$this->wished_by])->one();
		if(!$profile)
			return User::findOne($this->wished_by)->username;
		
		return "$profile->firstname $profile->lastname";
	}	
    /**
     * @returns the name of the wisher
     */	
	public function getWisherPic(){
		
		$profile = UserProfile::find()->where(['user_id'=>$this->wished_by])->one();
		if($profile && $profile->profile_image!='')
			return Yii::$app->homeUrl.$profile->profile_image;
		
		else return Yii::$app->homeUrl."images/default_profile.png";
	}	

	/**
     * @returns the name of the Granted wisher
     */	
	public function getGrantedWisherName(){
		
		$profile = UserProfile::find()->where(['user_id'=>$this->granted_by])->one();
		if(!$profile)
			return "";
		
		return "$profile->firstname $profile->lastname";
	}		
	
	
			
	public function sendCreateSuccessEmail($id)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>9])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
		
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $id,
        ]);
			
        if (!$user) {
            return false;
        }
      
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'wishcreateSuccess-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo( $user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	public function sendUpdateSuccessEmail($id)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>10])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
		
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $id,
        ]);
			
        if (!$user) {
            return false;
        }
      
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'wishupdateSuccess-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo( $user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	
	public function sendGrantWishNonFinancialEmail($id,$model)
    {
		
		 $mailcontent = MailContent::find()->where(['m_id'=>12])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
	
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $id,
        ]);
			
        if (!$user) {
            return false;
        }
      
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'grantwishcontactMessage-html'],
                ['user' => $user, 'editmessage' => $editmessage,'model' =>$model ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo( $user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send(); 
    }
	
	
}
