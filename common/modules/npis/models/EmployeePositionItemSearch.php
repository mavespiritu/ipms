<?php

namespace common\modules\npis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\npis\models\EmployeePositionItem;
use Yii;
/**
 * EmployeePositionItemSearch represents the model behind the search form of `common\modules\npis\models\EmployeePositionItem`.
 */
class EmployeePositionItemSearch extends EmployeePositionItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_no', 'position_id', 'division_id', 'grade', 'step', 'status'], 'safe'],
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
        $query = EmployeePositionItem::find();

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

        $query->andFilterWhere(['like', 'item_no', $this->item_no])
            ->andFilterWhere(['like', 'position_id', $this->position_id])
            ->andFilterWhere(['like', 'division_id', $this->division_id])
            ->andFilterWhere(['like', 'grade', $this->grade])
            ->andFilterWhere(['like', 'step', $this->step])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
