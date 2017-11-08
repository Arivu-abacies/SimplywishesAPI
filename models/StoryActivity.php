<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activities".
 *
 * @property integer $a_id
 * @property integer $story_id
 * @property integer $user_id
 * @property string $activity
 * @property string $created_at
 */
class StoryActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'story_activities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['story_id', 'user_id', 'activity'], 'required'],
            [['story_id', 'user_id'], 'integer'],
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
            'story_id' => 'Wish ID',
            'user_id' => 'User ID',
            'activity' => 'Activity',
            'created_at' => 'Created At',
        ];
    }
}
