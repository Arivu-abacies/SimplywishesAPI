<?php

namespace app\models;

use Yii;
use app\models\MailContent;
//use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
//class ContactForm extends Model
class ContactForm extends \yii\db\ActiveRecord
{
    /* public $name;
    public $email;
    public $subject;
    public $phone_number;
    public $body;  */
    public $verifyCode;

	
	public static function tableName()
    {
        return 'contact';
    }
	

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body','phone_number'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [['phone_number'],'integer'],
    
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

	
	   /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => 'Contact ID',
            'name' => 'Name',
            'email' => 'Email',
            'subject' => 'Subject',
            'body' => 'Body',
            'phone_number' => 'Phone Number',
            'created_at' => 'Created At',
			'verifyCode' => 'Verification Code',
        ];
    }
	
    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact()
    {	
		
		$mailcontent = MailContent::find()->where(['m_id'=>1])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
		
		
		$message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'contactMail-html'],
                ['username' => $this->name, 'editmessage' => $editmessage ]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($this->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');		
		$message->send();
		
		return true;
		
    }
	
	
	 public function admincontact()
    {	
		
		 /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'role' => 'admin',
        ]);
			
        if (!$user) {
            return false;
        }
		
		
		$subject = 	'SimplyWishes Contact Us ';
		
		$message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'admincontactMail-html'],
                ['contactname' => $this->name, 'contactemail' => $this->email , 'contactphone' => $this->phone_number , 'contactsubject' => $this->subject , 'contactmesssage' => $this->body]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($user->email)
            ->setSubject($subject);			
            
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');		
		$message->send();
		
		return true;
		
    }
	
	
}
