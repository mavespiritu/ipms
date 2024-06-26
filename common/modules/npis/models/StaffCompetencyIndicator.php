<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "staff_competency_indicator".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $position_id
 * @property int|null $indicator_id
 */
class StaffCompetencyIndicator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_competency_indicator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indicator_id', 'compliance'], 'integer'],
            [['emp_id'], 'string', 'max' => 20],
            [['position_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emp_id' => 'Emp ID',
            'position_id' => 'Position ID',
            'indicator_id' => 'Indicator ID',
            'compliance' => 'Compliance'
        ];
    }

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    public function getStaffCompetencyIndicatorEvidences()
    {
        return $this->hasMany(StaffCompetencyIndicatorEvidence::className(), ['emp_id' => 'emp_id', 'indicator_id' => 'indicator_id']);
    }
}
