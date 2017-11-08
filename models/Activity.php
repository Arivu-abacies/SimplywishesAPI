<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activities".
 *
 * @property integer $a_id
 * @property integer $wish_id
 * @property integer $user_id
 * @property string $activity
 * @property string $created_at
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wish_id', 'user_id', 'activity'], 'required'],
            [['wish_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['activity'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'wish_id' => 'Wish ID',
            'user_id' => 'User ID',
            'activity' => 'Activity',
            'created_at' => 'Created At',
        ];
    }
}
