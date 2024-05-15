<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "evidence_training".
 *
 * @property int $id
 * @property int|null $evidence_id
 * @property string|null $emp_id
 * @property int|null $seminar_title
 * @property string|null $from_date
 *
 * @property StaffCompetencyIndicatorEvidence $evidence
 */
class EvidenceTraining extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evidence_training';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['seminar_title'], 'required'],
            [['evidence_id'], 'integer'],
            [['from_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['seminar_title'], 'string', 'max' => 500],
            [['evidence_id'], 'exist', 'skipOnError' => true, 'targetClass' => StaffCompetencyIndicatorEvidence::className(), 'targetAttribute' => ['evidence_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'evidence_id' => 'Evidence ID',
            'emp_id' => 'Emp ID',
            'seminar_title' => 'Seminar Title',
            'from_date' => 'From Date',
        ];
    }

    /**
     * Gets query for [[Evidence]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvidence()
    {
        return $this->hasOne(StaffCompetencyIndicatorEvidence::className(), ['id' => 'evidence_id']);
    }

    public function getTraining()
    {
        return $this->hasOne(EmployeeTraining::className(), ['emp_id' => 'emp_id', 'seminar_title' => 'seminar_title', 'from_date' => 'from_date']);
    }
}
