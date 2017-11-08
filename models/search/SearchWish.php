<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Wish;
use app\models\Activity;

/**
 * SearchWish represents the model behind the search form about `app\models\Wish`.
 */
class SearchWish extends Wish
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['w_id', 'wished_by', 'granted_by', 'category', 'state', 'country', 'city'], 'integer'],
            [['wish_title', 'summary_title', 'wish_description', 'primary_image'], 'safe'],
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
	
	/* Custom single field search for wishes
	 * Currently searching only wish title, country,state and city
	 * @param array $params
	 * @return ActiveDataProvider
	 */
	public function searchCustom($params)
	{
		$keywords = explode(",",$params['match']);
		$query = Wish::find()->orderBy('w_id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$query->innerJoinWith('countryModel as countries');
		$query->innerJoinWith('stateModel as states');
		$query->innerJoinWith('cityModel as cities');
		
		foreach($keywords as $key=>$search){
			$search = trim($search);
			//if there are multiple searches, we are `and`ing the `or` queries
			if($key>0)
				$query->andFilterWhere(['or',
				['like', 'wish_title',$search],
				['like', 'summary_title',$search],
				['like', 'countries.name', $search],
				['like', 'states.name', $search],
				['like', 'cities.name', $search]]);		
			else
				$query->where(['like', 'wish_title',$search])
					->orFilterWhere(['like', 'summary_title', $search])
					->orFilterWhere(['like', 'countries.name', $search])
					->orFilterWhere(['like', 'states.name', $search])
					->orFilterWhere(['like', 'cities.name', $search]);					
					
		}
		
        return $dataProvider;
	}
	
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$cat_id=null)
    {
        $query = Wish::find()->where(['granted_by' => null,'wish_status'=>0])->orderBy('w_id DESC');
		if($cat_id)
			$query->andWhere(['category'=>$cat_id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>5
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id,
            'wished_by' => $this->wished_by,
            'granted_by' => $this->granted_by,
            'category' => $this->category,
            'state' => $this->state,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title])
            ->andFilterWhere(['like', 'summary_title', $this->summary_title])
            ->andFilterWhere(['like', 'wish_description', $this->wish_description])
            ->andFilterWhere(['like', 'primary_image', $this->primary_image]);

        return $dataProvider;
    }
	

	public function searchPopular($params){
        $query = Wish::find()->select(['wishes.*,count(a_id) as likes' ])->orderBy('likes DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>5
            ]
        ]);
		$query->joinWith(['likes as activity']);
		$query->groupBy('w_id');
		//echo $query->createCommand()->getRawSql();die;
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id,
            'wished_by' => $this->wished_by,
            'granted_by' => $this->granted_by,
            'category' => $this->category,
            'state' => $this->state,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title])
            ->andFilterWhere(['like', 'summary_title', $this->summary_title])
            ->andFilterWhere(['like', 'wish_description', $this->wish_description])
            ->andFilterWhere(['like', 'primary_image', $this->primary_image]);

        return $dataProvider;		
	}
	public function searchGranted($params){
        $query = Wish::find()->where(['not', ['granted_by' => null]])->orderBy('w_id DESC');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>5
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id,
            'wished_by' => $this->wished_by,
            'granted_by' => $this->granted_by,
            'category' => $this->category,
            'state' => $this->state,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title])
            ->andFilterWhere(['like', 'summary_title', $this->summary_title])
            ->andFilterWhere(['like', 'wish_description', $this->wish_description])
            ->andFilterWhere(['like', 'primary_image', $this->primary_image]);
			
        return $dataProvider;			
	}
	public function searchUserWishes($params, $user_id, $type){
		
        $query = Wish::find()->where(['wished_by'=>$user_id,'wish_status'=>0,'process_status'=>0])->orderBy('w_id DESC');
        // add conditions that should always apply here
		if($type == 'fullfilled')
			$query->andWhere(['not', ['granted_by' => null]]);
		else
			$query->andWhere(['granted_by' => null]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>0
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id,
            'wished_by' => $this->wished_by,
            'granted_by' => $this->granted_by,
            'category' => $this->category,
            'state' => $this->state,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title])
            ->andFilterWhere(['like', 'summary_title', $this->summary_title])
            ->andFilterWhere(['like', 'wish_description', $this->wish_description])
            ->andFilterWhere(['like', 'primary_image', $this->primary_image]);

        return $dataProvider;		
	}

	public function searchSavedWishes($params, $user_id){
		
		$query = Wish::find()->where(['activity.user_id'=>$user_id])->orderBy('w_id DESC');
		$query->innerJoinWith(['saved as activity']);
		$query->groupBy('w_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>0
            ]
        ]);
		return $dataProvider;	
	}
	
	
	 /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchReport($params)
    {
        $query = Wish::find()->all();
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>5
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id     
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title]);

        return $dataProvider;
    }
	

	public function searchDrafts($params)
    {
        $query = Wish::find()->where(['wish_status' => 1,'wished_by' =>Yii::$app->user->id ])->orderBy('w_id DESC');
	
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>10
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id,
            'wished_by' => $this->wished_by,
            'granted_by' => $this->granted_by,
            'category' => $this->category,
            'state' => $this->state,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title])
            ->andFilterWhere(['like', 'summary_title', $this->summary_title])
            ->andFilterWhere(['like', 'wish_description', $this->wish_description])
            ->andFilterWhere(['like', 'primary_image', $this->primary_image]);

        return $dataProvider;
    }
	
	public function searchProcessWishes($params, $user_id, $type){
		
        $query = Wish::find()->where(['wished_by'=>$user_id,'wish_status'=>0,'process_status'=>1 ])->orderBy('w_id DESC');
        // add conditions that should always apply here
		if($type == 'fullfilled')
			$query->andWhere(['not', ['granted_by' => null]]);
		else
			$query->andWhere(['granted_by' => null]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize'=>0
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'w_id' => $this->w_id,
            'wished_by' => $this->wished_by,
            'granted_by' => $this->granted_by,
            'category' => $this->category,
            'state' => $this->state,
            'country' => $this->country,
            'city' => $this->city,
        ]);

        $query->andFilterWhere(['like', 'wish_title', $this->wish_title])
            ->andFilterWhere(['like', 'summary_title', $this->summary_title])
            ->andFilterWhere(['like', 'wish_description', $this->wish_description])
            ->andFilterWhere(['like', 'primary_image', $this->primary_image]);

        return $dataProvider;		
	}

	
	
}
