<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "staff_all_indicator".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property int|null $indicator_id
 * @property int|null $compliance
 */
class StaffAllIndicator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_all_indicator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indicator_id', 'compliance'], 'integer'],
            [['emp_id'], 'string', 'max' => 20],
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
            'indicator_id' => 'Indicator ID',
            'compliance' => 'Compliance',
        ];
    }

    public function getStaffCompetencyIndicatorEvidences()
    {
        return $this->hasMany(StaffCompetencyIndicatorEvidence::className(), ['emp_id' => 'emp_id', 'indicator_id' => 'indicator_id']);
    }
}
