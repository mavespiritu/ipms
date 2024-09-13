<?php

namespace common\modules\npis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\npis\models\CareerPath;

/**
 * DesignationSearch represents the model behind the search form of `common\modules\npis\models\CareerPath`.
 */
class DesignationSearch extends CareerPath
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['emp_id', 'position_id', 'type', 'start_date', 'end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = CareerPath::find();

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
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['like', 'emp_id', $this->emp_id])
            ->andFilterWhere(['like', 'position_id', $this->position_id])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
