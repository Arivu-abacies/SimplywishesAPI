<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "editorial".
 *
 * @property integer $e_id
 * @property string $e_title
 * @property string $e_text
 * @property string $e_image
 * @property integer $status
 * @property string $created_at
 */
class Editorial extends \yii\db\ActiveRecord
{
	public $featured_video_upload;
		
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'editorial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_title', 'e_text', ], 'required'],
			[['e_image'], 'required','except' => 'update_by_editorial_admin'], 
            [['e_text'], 'string'],
            [['featured_video_url'], 'string'],
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['e_title'], 'string', 'max' => 250],
			[['e_image'], 'file','extensions' => 'jpg,png'],
			[['featured_video_upload'], 'file','extensions' => 'mp4,m4v,webm,ogv', 'skipOnEmpty' => true],
         
        ];
    }

	
	public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update_by_editorial_admin'] = ['e_title','e_text','e_image','featured_video_url']; //Scenario Values Only Accepted
        return $scenarios;
    } 
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'e_id' => 'E ID',
            'e_title' => 'Title',
            'e_text' => 'Text',
            'e_image' => 'Image',
            'status' => 'Status',
            'created_at' => 'Created At',
            'featured_video_url' => 'Featured Video',
			'featured_video_upload' => 'Feattured Video Upload',
        ];
    }
	
	public function uploadImage(){	
			$this->e_image->saveAs('uploads/editorial/' . $this->e_image->baseName . '.' .$this->e_image->extension);
			$this->e_image = 'uploads/editorial/'.$this->e_image->baseName .'.'.$this->e_image->extension;
			return true;	
	}
}
