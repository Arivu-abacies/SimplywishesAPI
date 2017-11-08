<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReportWishes;

/**
 * SearchEditorial represents the model behind the search form about `app\models\ReportWishes`.
 */
class SearchReportWishes extends ReportWishes
{
	
public $wishtitle;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rpt_id', 'count','w_id'], 'integer'],
			[['wishtitle'], 'safe'],			
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
        $query = ReportWishes::find()->orderBy('rpt_id Desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$query->joinWith(['wish as wishes']);	
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rpt_id' => $this->rpt_id,
            'w_id' => $this->w_id,
            'count' => $this->count,          
        ]);
		
		 $query->andFilterWhere(['like', 'wishes.wish_title', $this->wishtitle]);
		 
        return $dataProvider;
    }
}
