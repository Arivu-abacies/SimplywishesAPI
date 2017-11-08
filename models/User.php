<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
	public $password;
	public $verify_password;

	
    const STATUS_DELETED = 12;
    const STATUS_ACTIVE = 10;   
    const STATUS_INACTIVE = 13;   
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    public function rules()
    {
        return [
			[['username', 'email'],'required'],
			[['password','verify_password'], 'required', 'on'=>'sign-up'],
			[['username'], 'string', 'max' => 255],
			['email', 'email'],
			[['username'], 'unique','targetClass' => '\app\models\User', 'message' => 'This Username has already been taken.'],
		
		  [['email'], 'unique','targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.','on'=>['sign-up','updatecheck'] ],  
			
			//['email', 'uniqueEmail','on'=>'updatecheck'],

			[['password','verify_password'], 'string','min'=>6,'max'=>15],
			[['verify_password'],'compare','compareAttribute'=>'password','message'=>'Password do not match'],
		];
	}
	public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['username', 'email','password','verify_password'];
		$scenarios['apply_forgotpassword'] = ['email'];//Scenario Values Only Accepted
		$scenarios['updatecheck'] = ['email'];//Scenario Values Only Accepted
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {	// username or  email function 
        //return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
		
		$val = static::find()->where("status = ".self::STATUS_ACTIVE." AND (username = '$username' OR email = '$username')")->one();		
		 return $val;
		 
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
	
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

	  public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
	
	
	 public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
	
	
	 public static function findByAuthKeyValidation($auth_key)
    {        
        return static::findOne([
            'auth_key' => $auth_key,
            'status' => self::STATUS_INACTIVE,
        ]);
    }
	
	/*  public function uniqueEmail($attribute, $params)
    {
	
        $user = \app\models\User::find()->where('email=:email',array('email'=>$this->email))->all();
		$check = "start";
		
		if(isset($user) && !empty($user))
		{
			foreach($user as $tmp)
			{
				if(\Yii::$app->user->id != $tmp->id)
				{					
					$check = "end";
					
				}
			}
		}
		
		if(	$check == "end")
		{
		
            return $this->addError("email", 'Email already test  exists!');
		} 
		

		
    }  */
	
}
