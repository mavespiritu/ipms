<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "competency_indicator".
 *
 * @property int $id
 * @property int|null $competency_id
 * @property int|null $proficiency
 * @property string|null $indicator
 *
 * @property Competency $competency
 */
class CompetencyIndicator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'competency_indicator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['competency_id', 'proficiency'], 'integer'],
            [['indicator'], 'string'],
            [['competency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Competency::className(), 'targetAttribute' => ['competency_id' => 'comp_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'competency_id' => 'Competency',
            'proficiency' => 'Proficiency Level',
            'indicator' => 'Indicator',
        ];
    }

    /**
     * Gets query for [[Competency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetency()
    {
        return $this->hasOne(Competency::className(), ['comp_id' => 'competency_id']);
    }
}
