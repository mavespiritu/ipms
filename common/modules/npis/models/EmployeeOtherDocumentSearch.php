<?php

namespace common\modules\npis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\npis\models\EmployeeOtherDocument;
use Yii;
/**
 * EmployeeOtherDocumentSearch represents the model behind the search form of `common\modules\npis\models\EmployeeOtherDocument`.
 */
class EmployeeOtherDocumentSearch extends EmployeeOtherDocument
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'subject', 'from_date', 'approval', 'approver'], 'safe'],
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
        $query = EmployeeOtherDocument::find();

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
            'emp_id' => $this->emp_id,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'from_date', $this->from_date])
            ->andFilterWhere(['like', 'approval', $this->approval])
            ->andFilterWhere(['like', 'approver', $this->approver]);

        $query = !Yii::$app->user->can('HR') ? $query->andWhere(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N]) : $query;
        $query = $query->orderBy(['from_date' => SORT_DESC, 'subject' => SORT_ASC,]);

        return $dataProvider;
    }
}
