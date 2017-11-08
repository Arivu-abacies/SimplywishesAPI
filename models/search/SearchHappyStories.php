<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HappyStories;

/**
 * SearchEditorial represents the model behind the search form about `app\models\HappyStories`.
 */
class SearchHappyStories extends HappyStories
{
	

public $fullname;
public $wishtitle;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hs_id', 'status','user_id','wish_id'], 'integer'],
            [['story_text', 'story_image', 'created_at','fullname','wishtitle'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
	 
    public function search($params)
    {
        $query = HappyStories::find()->orderBy('hs_id Desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			
        ]);
		$query->joinWith(['author as user_profile']);	
		$query->joinWith(['wish as wishes']);	
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'hs_id' => $this->hs_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'story_text', $this->story_text])
        ->andFilterWhere(['like', 'wishes.wish_title', $this->wishtitle])   
		->andFilterWhere(['or',
            ['like','user_profile.firstname',$this->fullname],
            ['like','user_profile.lastname',$this->fullname]]);
            
           
        return $dataProvider;
    }
	
	public function searchLive($params)
	{
		
     $query = HappyStories::find()->where(['status'=>0])->orderBy('hs_id Desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
                'pageSize'=>10
            ]
        ]);
		$query->joinWith(['author as user_profile']);	
		$query->joinWith(['wish as wishes']);	
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'hs_id' => $this->hs_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'story_text', $this->story_text])
        ->andFilterWhere(['like', 'wishes.wish_title', $this->wishtitle])   
		->andFilterWhere(['or',
            ['like','user_profile.firstname',$this->fullname],
            ['like','user_profile.lastname',$this->fullname]]);
            
           
        return $dataProvider;	
	}
	
	
	public function searchMystories($params)
	{
		
        $query = HappyStories::find()->where(['`happy_stories`.user_id'=>\Yii::$app->user->id])->orderBy('hs_id Desc');
	
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
                'pageSize'=>10
            ]
        ]);
		$query->joinWith(['author as user_profile']);	
		$query->joinWith(['wish as wishes']);	
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'hs_id' => $this->hs_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'story_text', $this->story_text])
        ->andFilterWhere(['like', 'wishes.wish_title', $this->wishtitle])   
		->andFilterWhere(['or',
            ['like','user_profile.firstname',$this->fullname],
            ['like','user_profile.lastname',$this->fullname]]);
            
           
        return $dataProvider;	
	}
	
	
}
