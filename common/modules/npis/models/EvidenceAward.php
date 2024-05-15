<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "evidence_award".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $type
 * @property string|null $description
 */
class EvidenceAward extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evidence_award';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['evidence_id'], 'integer'],
            [['emp_id'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'description' => 'Award',
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

    public function getAward()
    {
        return $this->hasOne(EmployeeOtherInfo::className(), ['emp_id' => 'emp_id', 'type' => 'type', 'description' => 'description']);
    }
}
