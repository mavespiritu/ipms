<?php

namespace common\modules\dtr\models;

use Yii;
use common\models\Employee;

/**
 * This is the model class for table "tblactual_dtr".
 *
 * @property string $emp_id
 * @property string $date
 * @property string $time
 * @property string $time_in
 * @property string|null $time_out
 * @property string|null $year
 * @property string|null $month
 *
 * @property Tblemployee $emp
 */
class Dtr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblactual_dtr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'time_in', 'time_out'], 'safe'],
            [['emp_id', 'month'], 'string', 'max' => 20],
            [['time', 'year'], 'string', 'max' => 10],
            [['emp_id', 'date', 'time', 'time_in'], 'unique', 'targetAttribute' => ['emp_id', 'date', 'time', 'time_in']],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'emp_id' => 'Emp ID',
            'date' => 'Date',
            'time' => 'Time',
            'time_in' => 'Time In',
            'time_out' => 'Time Out',
            'year' => 'Year',
            'month' => 'Month',
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
}
