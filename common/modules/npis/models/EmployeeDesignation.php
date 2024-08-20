<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "employee_designation".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $position_id
 * @property string|null $start_date
 * @property string|null $end_date
 */
class EmployeeDesignation extends \yii\db\ActiveRecord
{
    public $item_no;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee_designation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id', 'start_date'], 'required'],
            [['start_date', 'end_date'], 'safe'],
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
            'position_id' => 'Position',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'item_no' => 'Item No.'
        ];
    }

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
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
    public function getPositionItem()
    {
        return $this->hasOne(EmployeePositionItem::className(), ['item_no' => 'position_id']);
    }

    public function getItem_no()
    {
        return $this->position_id;
    }
}
