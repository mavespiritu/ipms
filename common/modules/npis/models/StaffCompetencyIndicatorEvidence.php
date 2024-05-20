<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "staff_competency_indicator_evidence".
 *
 * @property int $id
 * @property int|null $staff_competency_indicator
 * @property string|null $justification
 * @property string|null $start_date
 * @property string|null $end_date
 */
class StaffCompetencyIndicatorEvidence extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_competency_indicator_evidence';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'start_date', 'end_date'], 'required', 'on' => 'otherEvidence'],
            [['indicator_id'], 'integer'],
            [['emp_id', 'description'], 'string'],
            [['start_date', 'end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emp_id' => 'Employee ID',
            'indicator_id' => 'Indicator',
            'title' => 'Title',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'reference' => 'Reference',
            'hr_confirmation' => 'HR Confirmation',
            'hr_date' => 'HR Confirmation Date',
            'hr_remarks' => 'HR Remarks',
            'dc_confirmation' => 'DC Confirmation',
            'dc_date' => 'DC Confirmation Date',
            'dc_remarks' => 'DC Remarks',
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

    public function getStaffCompetencyIndicators()
    {
        return $this->hasMany(StaffCompetencyIndicator::className(), ['emp_id' => 'emp_id', 'indicator_id' => 'indicator_id']);
    }

    public function getEvidenceTraining()
    {
        return $this->hasOne(EvidenceTraining::className(), ['evidence_id' => 'id']);
    }

    public function getEvidenceAward()
    {
        return $this->hasOne(EvidenceAward::className(), ['evidence_id' => 'id']);
    }

    public function getEvidencePerformance()
    {
        return $this->hasOne(EvidencePerformance::className(), ['evidence_id' => 'id']);
    }
}
