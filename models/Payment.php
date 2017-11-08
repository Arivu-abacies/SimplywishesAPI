<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property integer $p_id
 * @property string $item_name
 * @property string $item_number
 * @property string $payment_status
 * @property string $payment_amount
 * @property string $payment_currency
 * @property string $txn_id
 * @property string $receiver_email
 * @property string $payer_email
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'item_number', 'payment_status', 'txn_id', 'receiver_email', 'payer_email'], 'string', 'max' => 255],
            [['payment_amount', 'payment_currency'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'item_name' => 'Item Name',
            'item_number' => 'Item Number',
            'payment_status' => 'Payment Status',
            'payment_amount' => 'Payment Amount',
            'payment_currency' => 'Payment Currency',
            'txn_id' => 'Txn ID',
            'receiver_email' => 'Receiver Email',
            'payer_email' => 'Payer Email',
        ];
    }
}
