<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\ArrayHelper;
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
            [['competency', 'comp_type'], 'required'],
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
            'comp_type' => 'Type',
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

    public function getStaffCompetencyPercentage($emp_id)
    {
        $model = EmployeeItem::find()
            ->andWhere([
                'emp_id' => $emp_id
            ])
            ->andWhere([
                'is', 'to_date', null
            ])
            ->orderBy([
                'from_date' => SORT_DESC
            ])
            ->one();

        $competencyIndicatorsCount = PositionCompetencyIndicator::find()
                    ->leftJoin('competency_indicator', 'competency_indicator.id = position_competency_indicator.indicator_id')
                    ->where([
                        'competency_indicator.competency_id' => $this->comp_id,
                        'position_id' => $model->item_no
                    ])
                    ->count();

        $proficiencies = PositionCompetencyIndicator::find()
                        ->select(['distinct(competency_indicator.proficiency) as proficiency'])
                        ->leftJoin('competency_indicator', 'competency_indicator.id = position_competency_indicator.indicator_id')
                        ->where([
                            'competency_indicator.competency_id' => $this->comp_id,
                            'position_id' => $model->item_no
                        ])
                        ->asArray()
                        ->all();

        $proficiencies = ArrayHelper::map($proficiencies, 'proficiency', 'proficiency');

        $staffIndicatorsCount = StaffCompetencyIndicator::find()
                    ->leftJoin('competency_indicator', 'competency_indicator.id = staff_competency_indicator.indicator_id')
                    ->andWhere([
                        'emp_id' => $emp_id,
                        'compliance' => 1,
                        'position_id' => $model->item_no,
                        'competency_indicator.competency_id' => $this->comp_id,
                        'competency_indicator.proficiency' => $proficiencies
                    ])
                    ->count();

        return $competencyIndicatorsCount > 0 ? ($staffIndicatorsCount/$competencyIndicatorsCount)*100 : 0;
    }

    public function getStaffAllCompetencyPercentage($emp_id)
    {
        $competencyIndicatorsCount = CompetencyIndicator::find()
                    ->where([
                        'competency_id' => $this->comp_id,
                    ])
                    ->count();
                    
        $staffIndicatorsCount = StaffCompetencyIndicator::find()
                    ->leftJoin('competency_indicator', 'competency_indicator.id = staff_competency_indicator.indicator_id')
                    ->andWhere([
                        'emp_id' => $emp_id,
                        'compliance' => 1,
                        'competency_indicator.competency_id' => $this->comp_id
                    ])
                    ->count();

        return $competencyIndicatorsCount > 0 ? ($staffIndicatorsCount/$competencyIndicatorsCount)*100 : 0;
    }
}
