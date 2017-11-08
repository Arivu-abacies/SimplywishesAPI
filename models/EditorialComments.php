<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "editorial_comments".
 *
 * @property integer $e_com_id
 * @property integer $e_id
 * @property integer $user_id
 * @property string $comments
 * @property string $created_at
 * @property integer $status
 */
class EditorialComments extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'editorial_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_id', 'user_id', 'comments'], 'required'],
            [['e_id', 'user_id', 'status','parent_id'], 'integer'],
            [['comments'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'e_comment_id' => 'E Com ID',
            'parent_id' => 'Parent ID',
            'e_id' => 'E ID',
            'user_id' => 'User ID',
            'comments' => 'Comments',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }
}
