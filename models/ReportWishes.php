<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "report_wishes".
 *
 * @property integer $rpt_id
 * @property integer $w_id
 * @property integer $count
 */
class ReportWishes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_wishes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['w_id', 'count'], 'required'],
            [['w_id', 'count'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rpt_id' => 'Rpt ID',
            'w_id' => 'W ID',
            'count' => 'Count',
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getWish()
    {
        return $this->hasOne(Wish::className(), ['w_id' => 'w_id']);
    }	
	
}
