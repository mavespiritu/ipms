<?php

namespace common\modules\dtr\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_dtr_type".
 *
 * @property string $emp_id
 * @property string $dtr_id
 * @property string $date
 * @property string|null $total_with_out_pass_slip
 * @property string|null $total_with_pass_slip
 * @property string|null $total_tardy
 * @property string|null $total_UT
 * @property string|null $total_pass_slip
 * @property string|null $am_in
 * @property string|null $am_out
 * @property string|null $pm_in
 * @property string|null $pm_out
 * @property string|null $total_OT
 * @property string|null $multiplied_total_OT
 *
 * @property Tblemployee $emp
 * @property TbldtrType $dtr
 */
class EmployeeDtrType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_dtr_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['emp_id', 'dtr_id', 'total_with_out_pass_slip', 'total_with_pass_slip', 'total_tardy', 'total_UT', 'total_pass_slip', 'am_in', 'am_out', 'pm_in', 'pm_out', 'total_OT', 'multiplied_total_OT'], 'string', 'max' => 20],
            [['emp_id', 'dtr_id', 'date'], 'unique', 'targetAttribute' => ['emp_id', 'dtr_id', 'date']],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
            [['dtr_id'], 'exist', 'skipOnError' => true, 'targetClass' => DtrType::className(), 'targetAttribute' => ['dtr_id' => 'dtr_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'emp_id' => 'Emp ID',
            'dtr_id' => 'Dtr ID',
            'date' => 'Date',
            'total_with_out_pass_slip' => 'Total With Out Pass Slip',
            'total_with_pass_slip' => 'Total With Pass Slip',
            'total_tardy' => 'Total Tardy',
            'total_UT' => 'Total  Ut',
            'total_pass_slip' => 'Total Pass Slip',
            'am_in' => 'Am In',
            'am_out' => 'Am Out',
            'pm_in' => 'Pm In',
            'pm_out' => 'Pm Out',
            'total_OT' => 'Total  Ot',
            'multiplied_total_OT' => 'Multiplied Total  Ot',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->ipms; // Return the second database connection
    }

    /**
     * Gets query for [[Emp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[Dtr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDtrType()
    {
        return $this->hasOne(DtrType::className(), ['dtr_id' => 'dtr_id']);
    }
}
