<?php

namespace common\modules\v1\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\v1\models\PpmpItem;

/**
 * PpmpItemSearch represents the model behind the search form of `common\modules\v1\models\PpmpItem`.
 */
class PpmpItemSearch extends PpmpItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'appropriation_item_id', 'activity_id', 'fund_source_id', 'sub_activity_id', 'obj_id', 'ppmp_id', 'item_id'], 'integer'],
            [['cost'], 'number'],
            [['remarks'], 'safe'],
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
        $query = PpmpItem::find()
                ->orderBy(['id' => SORT_DESC])
        ;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
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
            'appropriation_item_id' => $this->appropriation_item_id,
            'activity_id' => $this->activity_id,
            'fund_source_id' => $this->fund_source_id,
            'sub_activity_id' => $this->sub_activity_id,
            'obj_id' => $this->obj_id,
            'ppmp_id' => $this->ppmp_id,
            'item_id' => $this->item_id,
            'cost' => $this->cost,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }
}
