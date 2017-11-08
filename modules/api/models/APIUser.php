<?php

namespace app\modules\api\models;

use app\models\User;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class APIUser extends User
{
    /**
     * @inheritdoc
     */
     public static function findIdentityByAccessToken($token, $type = null)
    {
		return static::findOne(['auth_key' => $token]);
    } 

}