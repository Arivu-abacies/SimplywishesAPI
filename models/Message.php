<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property integer $m_id
 * @property integer $sender_id
 * @property integer $recipient_id
 * @property integer $parent_id
 * @property string $text
 * @property string $created_at
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'recipient_id', 'parent_id', 'text'], 'required'],
            [['sender_id', 'recipient_id', 'parent_id'], 'integer'],
            [['created_at'], 'safe'],
            [['text'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'm_id' => 'M ID',
            'sender_id' => 'Sender ID',
            'recipient_id' => 'Recipient ID',
            'parent_id' => 'Parent ID',
            'text' => 'Text',
            'created_at' => 'Created At',
        ];
    }
}
