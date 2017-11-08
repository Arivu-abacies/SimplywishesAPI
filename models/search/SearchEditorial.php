<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Editorial;

/**
 * SearchEditorial represents the model behind the search form about `app\models\Editorial`.
 */
class SearchEditorial extends Editorial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['e_id', 'status'], 'integer'],
            [['e_title', 'e_text', 'e_image', 'created_at'], 'safe'],
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
        $query = Editorial::find()->orderBy('e_id Desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'e_id' => $this->e_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'e_title', $this->e_title])
            ->andFilterWhere(['like', 'e_text', $this->e_text])
            ->andFilterWhere(['like', 'e_image', $this->e_image]);

        return $dataProvider;
    }
}
