<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use app\models\User;
use app\models\MailContent;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $verify_password;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
     public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            //throw new InvalidParamException('Password reset token cannot be blank.');
			return null;	
        }
        $this->_user = User::findByPasswordResetToken($token);
        if (!$this->_user) {
           //throw new InvalidParamException('Wrong password reset token.');
			return null;		   
        }
        parent::__construct($config);
    } 

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['verify_password', 'required'],
            [['password','verify_password'], 'string', 'min' => 6,'max'=>15],
			[['verify_password'],'compare','compareAttribute'=>'password','message'=>'Password do not match'],
        ];
    }

	
	  /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
         return [
            'password' => 'New Password',
            'verify_password' => 'Confirm Password',
          
        ]; 
    }
	
    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();
        return $user->save(false);
    }
	
	public function sendEmailResetSuccess()
    {  
		$mailcontent = MailContent::find()->where(['m_id'=>4])->one();
		$editmessage = $mailcontent->mail_message;		
		$subject = $mailcontent->mail_subject;
		if(empty($subject))
			$subject = 	'SimplyWishes ';
		
	
		$userdetails = $this->_user;
		
	 $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $userdetails->email,
        ]);
			
        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->removePasswordResetToken();
			$user->scenario = 'apply_forgotpassword';	
            if (!$user->save()) { 
                return false;
            }
        }
		
       $message = Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetSuccessMessage-html'],['user'=>$user , 'editmessage' => $editmessage ]               
            )
            ->setFrom([Yii::$app->params['supportEmail'] => 'SimplyWishes '])
            ->setTo($userdetails->email)
            ->setSubject($subject);
          			
		$message->getSwiftMessage()->getHeaders()->addTextHeader('MIME-version', '1.0\n');
		$message->getSwiftMessage()->getHeaders()->addTextHeader('charset', ' iso-8859-1\n');
		
		return $message->send();
    }	
}
