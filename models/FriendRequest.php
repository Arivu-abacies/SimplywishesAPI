<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "friend_request".
 *
 * @property integer $f_id
 * @property integer $requested_by
 * @property integer $requested_to
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class FriendRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'friend_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requested_by', 'requested_to'], 'required'],
            [['requested_by', 'requested_to', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'f_id' => 'F ID',
            'requested_by' => 'Requested By',
            'requested_to' => 'Requested To',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
	
	 public function sendEmail($to)
    {
        /* @var $user User */
         $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'id' => $to,
        ]); 
		
		
		 $fromuser = UserProfile::findOne([
            'user_id' => \Yii::$app->user->id,
        ]); 
		
        if (!$user) {
            return false;
        }
		
		if (!$fromuser) {
            return false;
        }
		
        $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'friendRequest-html'],
                ['user' => $user ,'fromuser'=>$fromuser]
                
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($user->email)
            ->setSubject('SimplyWishes Friend Requested Notification ');			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }
	
}
