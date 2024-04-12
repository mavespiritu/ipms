<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_work_experience_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $agency
 * @property string|null $position
 * @property string|null $appointment
 * @property string|null $grade
 * @property string|null $monthly_salary
 * @property string|null $date_start
 * @property string|null $step
 *
 * @property TblempAppointment[] $tblempAppointments
 * @property TblempDutyAssumption[] $tblempDutyAssumptions
 * @property TblempNosaNosi[] $tblempNosaNosis
 * @property TblempOfficeOath[] $tblempOfficeOaths
 * @property TblempPositionDescription[] $tblempPositionDescriptions
 */
class EmployeeWorkExperienceId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_work_experience_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_start'], 'safe'],
            [['emp_id', 'appointment'], 'string', 'max' => 20],
            [['agency'], 'string', 'max' => 255],
            [['position'], 'string', 'max' => 100],
            [['grade', 'step'], 'string', 'max' => 10],
            [['monthly_salary'], 'string', 'max' => 30],
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->ipms; // Return the second database connection
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
            'id' => 'ID',
            'emp_id' => 'Emp ID',
            'agency' => 'Agency',
            'position' => 'Position',
            'appointment' => 'Appointment',
            'grade' => 'Grade',
            'monthly_salary' => 'Monthly Salary',
            'date_start' => 'Date Start',
            'step' => 'Step',
        ];
    }

    /**
     * Gets query for [[TblempAppointments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeAppointments()
    {
        return $this->hasMany(EmployeeAppointment::className(), ['work_experience_id' => 'id']);
    }

    /**
     * Gets query for [[TblempDutyAssumptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeDutyAssumptions()
    {
        return $this->hasMany(EmployeeDutyAssumption::className(), ['work_experience_id' => 'id']);
    }

    /**
     * Gets query for [[TblempPositionDescriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePositionDescriptions()
    {
        return $this->hasMany(EmployeePositionDescription::className(), ['work_experience_id' => 'id']);
    }

    /**
     * Gets query for [[TblempOfficeOaths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeOfficeOaths()
    {
        return $this->hasMany(EmployeeOfficeOath::className(), ['work_experience_id' => 'id']);
    }

    /**
     * Gets query for [[TblempNosaNosis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeNosaNosis()
    {
        return $this->hasMany(EmployeeNosaNosi::className(), ['work_experience_id' => 'id']);
    }
}
