<?php

namespace common\modules\dtr\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tbldtr_type".
 *
 * @property string $dtr_id
 * @property string|null $dtr_description
 * @property string|null $am_time_in_early
 * @property string|null $am_time_in_late
 * @property string|null $am_time_out_early
 * @property string|null $am_time_out_late
 * @property string|null $pm_time_in_early
 * @property string|null $pm_time_in_late
 * @property string|null $pm_time_out_early
 * @property string|null $pm_time_out_late
 * @property string|null $ot_time_in_early
 * @property string|null $ot_time_in_late
 * @property string|null $ot_time_out_early
 * @property string|null $to_time_out_late
 * @property string|null $with_whole_day_deduction
 * @property float|null $deduction
 *
 * @property TbldtrTypeDailyDeduction $tbldtrTypeDailyDeduction
 * @property TblempDtrType[] $tblempDtrTypes
 * @property TblempDtrTypeDefault[] $tblempDtrTypeDefaults
 * @property TblempDtrTypePm[] $tblempDtrTypePms
 */
class DtrType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbldtr_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dtr_id'], 'required'],
            [['am_time_in_early', 'am_time_in_late', 'am_time_out_early', 'am_time_out_late', 'pm_time_in_early', 'pm_time_in_late', 'pm_time_out_early', 'pm_time_out_late', 'ot_time_in_early', 'ot_time_in_late', 'ot_time_out_early', 'to_time_out_late'], 'safe'],
            [['deduction'], 'number'],
            [['dtr_id', 'with_whole_day_deduction'], 'string', 'max' => 20],
            [['dtr_description'], 'string', 'max' => 255],
            [['dtr_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dtr_id' => 'Dtr ID',
            'dtr_description' => 'Dtr Description',
            'am_time_in_early' => 'Am Time In Early',
            'am_time_in_late' => 'Am Time In Late',
            'am_time_out_early' => 'Am Time Out Early',
            'am_time_out_late' => 'Am Time Out Late',
            'pm_time_in_early' => 'Pm Time In Early',
            'pm_time_in_late' => 'Pm Time In Late',
            'pm_time_out_early' => 'Pm Time Out Early',
            'pm_time_out_late' => 'Pm Time Out Late',
            'ot_time_in_early' => 'Ot Time In Early',
            'ot_time_in_late' => 'Ot Time In Late',
            'ot_time_out_early' => 'Ot Time Out Early',
            'to_time_out_late' => 'To Time Out Late',
            'with_whole_day_deduction' => 'With Whole Day Deduction',
            'deduction' => 'Deduction',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->ipms; // Return the second database connection
    }

    /**
     * Gets query for [[TbldtrTypeDailyDeduction]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTbldtrTypeDailyDeduction()
    {
        return $this->hasOne(TbldtrTypeDailyDeduction::className(), ['dtr_id' => 'dtr_id']);
    }
 */
    /**
     * Gets query for [[TblempDtrTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function EmployeeDtrTypes()
    {
        return $this->hasMany(EmployeeDtrType::className(), ['dtr_id' => 'dtr_id']);
    }

    /**
     * Gets query for [[TblempDtrTypeDefaults]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTblempDtrTypeDefaults()
    {
        return $this->hasMany(TblempDtrTypeDefault::className(), ['dtr_id' => 'dtr_id']);
    } */

    /**
     * Gets query for [[TblempDtrTypePms]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* s */
}
