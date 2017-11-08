<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mail_content".
 *
 * @property integer $m_id
 * @property string $mail_key
 * @property string $mail_type
 * @property string $mail_subject
 * @property string $mail_message
 * @property string $mail_variable
 * @property integer $status
 * @property string $created_at
 */
class MailContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_type', 'mail_subject', 'mail_message'], 'required'],
            [['mail_message', 'mail_variable'], 'string'],
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['mail_key', 'mail_type', 'mail_subject'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'm_id' => 'M ID',
            'mail_key' => 'Mail Key',
            'mail_type' => 'Mail Type',
            'mail_subject' => 'Mail Subject',
            'mail_message' => 'Mail Message',
            'mail_variable' => 'Mail Variable',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
