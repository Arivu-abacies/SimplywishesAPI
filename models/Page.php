<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property integer $p_id
 * @property string $title
 * @property string $content
 * @property string $page_id
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'page_id'], 'required'],
            [['title', 'content'], 'string'],
            [['page_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'title' => 'Title',
            'content' => 'Content',
            'page_id' => 'Page ID',
        ];
    }
}
