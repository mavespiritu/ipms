<?php

namespace common\modules\npis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\npis\models\Training;
use Yii;
/**
 * IpcrSearch represents the model behind the search form of `common\modules\npis\models\Training`.
 */
class TrainingSearch extends Training
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'service_provider_id', 'no_of_hours'], 'integer'],
            [['training_title', 'cost', 'modality'], 'safe'],
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
        $query = Training::find();

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
            'service_provider_id' => $this->service_provider_id,
        ]);

        $query->andFilterWhere(['like', 'training_title', $this->training_title])
            ->andFilterWhere(['like', 'no_of_hours', $this->no_of_hours])
            ->andFilterWhere(['like', 'cost', $this->cost])
            ->andFilterWhere(['like', 'modality', $this->modality]);

        $query = $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }
}
