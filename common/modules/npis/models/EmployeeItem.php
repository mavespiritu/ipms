<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;

/**
 * This is the model class for table "tblemp_emp_item".
 *
 * @property string $item_no
 * @property string $emp_id
 * @property string $from_date
 * @property string|null $to_date
 *
 * @property Tblemployee $emp
 * @property TblempPositionItem $itemNo
 */
class EmployeeItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_emp_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_no', 'emp_id', 'from_date'], 'required'],
            [['from_date', 'to_date'], 'safe'],
            [['item_no'], 'string', 'max' => 50],
            [['emp_id'], 'string', 'max' => 20],
            [['item_no', 'emp_id', 'from_date'], 'unique', 'targetAttribute' => ['item_no', 'emp_id', 'from_date']],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
            [['item_no'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeePositionItem::className(), 'targetAttribute' => ['item_no' => 'item_no']],
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
            'item_no' => 'Item No',
            'emp_id' => 'Emp ID',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
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
        return $this->hasOne(EmployeePositionItem::className(), ['item_no' => 'item_no']);
    }
}
