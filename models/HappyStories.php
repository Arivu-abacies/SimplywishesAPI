<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "happy_stories".
 *
 * @property integer $hs_id
 * @property integer $user_id
 * @property integer $wish_id
 * @property string $story_text
 * @property string $story_image
 * @property integer $status
 * @property string $created_at
 */
class HappyStories extends \yii\db\ActiveRecord
{
	public $dulpicate_image;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'happy_stories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'wish_id', 'story_text'], 'required','except' => 'update_by_happystory_adminuser'],
			
			//[['story_image'], 'required','except' => 'update_by_happystory_user'], 
			
            [['user_id', 'wish_id'], 'integer','except' => 'update_by_happystory_adminuser'],
            [['status'], 'integer','on' => 'update_by_happystory_adminuser'],
            [['story_text'], 'string'],
            [['created_at'], 'safe'],
			[['dulpicate_image'], 'safe'],			
			[['story_image'], 'file','extensions' => 'jpg,png'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'hs_id' => 'Hs ID',
            'user_id' => 'User ID',
            'wish_id' => 'Wish ID',
            'story_text' => 'Story Text',
            'story_image' => 'Story Image',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
	
	public function uploadImage(){	
	
		$this->story_image->saveAs('uploads/happystory/' . $this->story_image->baseName . '.' .$this->story_image->extension);
		$this->story_image = 'uploads/happystory/'.$this->story_image->baseName .'.'.$this->story_image->extension;
		return true;	
	}
	
    /**
     * @return no of likes
     */
    public function getLikesCount()
    {
        return StoryActivity::find()->where(['story_id'=>$this->hs_id,'activity'=>'like'])->count();
    }	
	
    /**
	 * Is this wish liked by the particular user
     * @return boolean
     */	
	public function isLiked($byUser){
		if(StoryActivity::find()->where(['story_id'=>$this->hs_id,'activity'=>'like','user_id'=>$byUser])->one()!= null)
			return true;
		else return false;
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWish()
    {
        return $this->hasOne(Wish::className(), ['w_id' => 'wish_id']);
    }	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'user_id']);
    }	
	
	public function getHappyAsCard(){
		  $str = '';
		$wish_details = $this->wish;
		$str .= '<div class="col-md-10 happystory smp-mg-bottom">
				<div class="media"> 
					<div class="media-left">';
					
		$str .= '<img alt="64x64" src="'.\Yii::$app->homeUrl.$this->story_image.'" class="media-object"   style="width: 200px;border: solid 2px #0cb370;">';
		
		$str .= '<span><i class="fa fa-thumbs-o-up fnt-blue"></i>'.$this->likesCount.' Likes</span>';
		$str .= '</div>';
		
		$str .= '<div class="media-body">'; 
		$str .=	'<h4 class="media-heading">'.$wish_details->wish_title.'</h4>';
		$str .= '<a href="'.Url::to(["account/profile","id"=>$this->user_id]).'">Author: '.$this->author->fullname.'</a>';
		$str .= '<p>'.substr($this->story_text,0,450).'</p>';
		$str .= '<a href="<?=Yii::$app->homeUrl?>happy-stories/story-details?id='.$this->hs_id.'" ><h5>Read More</h5></a>';
		$str .= '</div> 
				</div>
			</div>';
			
			
		echo $str;
	}
	
	
	public function getMyHappyAsCard(){
		  $str = '';
		$wish_details = $this->wish;
		$str .= '<div class="col-md-12 happystory smp-mg-bottom">
				<div class="media"> 
					<div class="media-left">';
					
		$str .= '<img alt="64x64" src="'.\Yii::$app->homeUrl.$this->story_image.'" class="media-object"   style="width: 200px;border: solid 2px #0cb370;">';
		
		$str .= '<span><i class="fa fa-thumbs-o-up fnt-blue"></i>'.$this->likesCount.' Likes</span>';
		$str .= '</div>';
		
		$str .= '<div class="media-body">'; 
		$str .=	'<h4 class="media-heading">'.$wish_details->wish_title.'</h4>';			
		$str .= '<a href="'.Url::to(["account/profile","id"=>$this->user_id]).'">Author: '.$this->author->fullname.'</a>';
		$str .= '<p>'.substr($this->story_text,0,450).'</p>';
		$str .= '<a href="<?=Yii::$app->homeUrl?>happy-stories/story-details?id='.$this->hs_id.'" ><h5>Read More</h5></a>';
		$str .= '</div> 
				</div>
			</div>';
			
			
		echo $str;
	}
	
	
		
	public function sendSuccessEmail($id)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>7])->one();
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
                ['html' => 'happystoriesSuccess-html'],
                ['user' => $user, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo( $user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
	public function sendAdminSuccessEmail($id)
    {
		
		$mailcontent = MailContent::find()->where(['m_id'=>8])->one();
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
                ['html' => 'happystoriesAdminSuccess-html'],
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
