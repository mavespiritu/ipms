<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "career_path".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $position_id
 */
class CareerPath extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'career_path';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id'], 'required'],
            [['emp_id', 'position_id', 'start_date'], 'required', 'on' => 'designationForm'],
            [['emp_id', 'start_date', 'end_date'], 'string', 'max' => 20],
            [['position_id'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 50],
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
            'id' => 'ID',
            'emp_id' => 'Employee',
            'position_id' => 'Position',
            'type' => 'Type',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
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
     * Gets query for [[ItemNo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeePositionItem()
    {
        return $this->hasOne(EmployeePositionItem::className(), ['item_no' => 'position_id']);
    }
}
