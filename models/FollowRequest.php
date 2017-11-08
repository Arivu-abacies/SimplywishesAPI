<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "follow_request".
 *
 * @property integer $foll_id
 * @property integer $requested_by
 * @property integer $requested_to
 * @property integer $status
 * @property string $created_at
 */
class FollowRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'follow_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requested_by', 'requested_to'], 'required'],
            [['requested_by', 'requested_to', 'status'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'foll_id' => 'Foll ID',
            'requested_by' => 'Requested By',
            'requested_to' => 'Requested To',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
