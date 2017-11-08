<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MailContent;

/**
 * SearchMailContent represents the model behind the search form about `app\models\MailContent`.
 */
class SearchMailContent extends MailContent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['m_id', 'status'], 'integer'],
            [['mail_key', 'mail_type', 'mail_subject', 'mail_message', 'mail_variable', 'created_at'], 'safe'],
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
        $query = MailContent::find();

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
            'm_id' => $this->m_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'mail_key', $this->mail_key])
            ->andFilterWhere(['like', 'mail_type', $this->mail_type])
            ->andFilterWhere(['like', 'mail_subject', $this->mail_subject])
            ->andFilterWhere(['like', 'mail_message', $this->mail_message])
            ->andFilterWhere(['like', 'mail_variable', $this->mail_variable]);

        return $dataProvider;
    }
}
