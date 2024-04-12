<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_references".
 *
 * @property string $emp_id
 * @property string $ref_name
 * @property string|null $address
 * @property string|null $tel_no
 *
 * @property Tblemployee $emp
 */
class EmployeeReference extends \yii\db\ActiveRecord
{
    public $id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_references';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ref_name', 'address', 'tel_no'], 'required'],
            [['emp_id', 'tel_no'], 'string', 'max' => 20],
            [['ref_name', 'address'], 'string', 'max' => 255],
            [['emp_id', 'ref_name'], 'unique', 'targetAttribute' => ['emp_id', 'ref_name']],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
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
            'ref_name' => 'Name',
            'address' => 'Address',
            'tel_no' => 'Telephone/Mobile No',
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
}
