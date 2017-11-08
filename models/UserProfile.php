<?php

namespace app\models;

use Yii;
use app\models\MailContent;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $profile_id
 * @property integer $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $about
 * @property integer $country
 * @property integer $state
 * @property integer $city
 * @property string $profile_image
 */
class UserProfile extends \yii\db\ActiveRecord
{
	
	public $dulpicate_image;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname'], 'required'],
            [['user_id', 'country', 'state', 'city'], 'integer'],
            [['about'], 'string'],
            [['dulpicate_image'], 'safe'],
			[['profile_image'], 'file','extensions' => 'jpg,png', 'skipOnEmpty' => true],
            [['firstname', 'lastname'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profile_id' => 'Profile ID',
            'user_id' => 'User ID',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'about' => 'About',
            'location' => 'Location',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'profile_image' => 'Profile Image',
        ];
    }
	
	public function uploadImage(){
		//if($this->validate()) {
			$this->profile_image->saveAs('uploads/' . $this->profile_image->baseName . '.' .$this->profile_image->extension);
			$this->profile_image = 'uploads/'.$this->profile_image->baseName.'.'.$this->profile_image->extension;
			return true;
		//}else
		//	return false;
	}
    /**
     * @returns the location of the wish
     */	
	public function getLocation(){
		
		$country = Country::findOne($this->country);
		$state = State::findOne($this->state);
		$city = City::findOne($this->city);
		
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
		
		/* else if(!empty($state) && !empty($country))
			$location  .= ", ".$country->name;
		else if(!empty($city) && !empty($country))
			$location  .= ", ".$country->name;
		else if(!empty($country))
			$location  .= $country->name;  */
		 
		return "$location";
		//return "$state->name , $city->name , $country->name";
	}
	
	public function getFullname(){
		return $this->firstname." ".$this->lastname;
	}
	
	public function sendVAlidationEmail($email)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>13])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
		
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_INACTIVE,
            'email' => $email,
        ]);
			
        if (!$user) {
            return false;
        }
      
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'signupValidationRegister-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	
	public function sendEmail($email)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>2])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
		
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $email,
        ]);
			
        if (!$user) {
            return false;
        }
      
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'signupRegister-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	public function sendProfileEmail($id)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>6])->one();
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
                ['html' => 'profileupdatedSuccess-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo( $user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	
}
