<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "competency".
 *
 * @property int $comp_id
 * @property string|null $competency
 * @property string|null $comp_type
 * @property string|null $description
 *
 * @property CompetencyType $compType
 * @property CompetencyIndicator[] $competencyIndicators
 * @property Tblintervention[] $tblinterventions
 */
class Competency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'competency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['competency'], 'string', 'max' => 255],
            [['comp_type'], 'string', 'max' => 50],
            [['comp_type'], 'exist', 'skipOnError' => true, 'targetClass' => CompetencyType::className(), 'targetAttribute' => ['comp_type' => 'comp_type']],
        ];
    }

    public function behaviors()
    {
        return [
            'fileBehavior' => [
                'class' => \file\behaviors\FileBehavior::className()
            ],
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comp_id' => 'Comp ID',
            'competency' => 'Competency',
            'comp_type' => 'Comp Type',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[CompType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompType()
    {
        return $this->hasOne(CompetencyType::className(), ['comp_type' => 'comp_type']);
    }

    /**
     * Gets query for [[CompetencyIndicators]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetencyIndicators()
    {
        return $this->hasMany(CompetencyIndicator::className(), ['competency_id' => 'comp_id']);
    }

    /**
     * Gets query for [[Tblinterventions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblinterventions()
    {
        return $this->hasMany(Tblintervention::className(), ['comp_id' => 'comp_id']);
    }
}
