<?php

namespace common\modules\npis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\npis\models\Ipcr;
use Yii;
/**
 * IpcrSearch represents the model behind the search form of `common\modules\npis\models\Ipcr`.
 */
class IpcrSearch extends Ipcr
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'year'], 'integer'],
            [['emp_id', 'semester', 'rating'], 'safe'],
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
        $query = Ipcr::find();

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
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'emp_id', $this->emp_id])
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'rating', $this->rating]);

        $query = !Yii::$app->user->can('HR') ? $query->andWhere(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N]) : $query;
        $query = $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }
}
