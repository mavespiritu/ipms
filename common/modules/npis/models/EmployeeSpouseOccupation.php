<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_spouse_occupation".
 *
 * @property string $emp_id
 * @property string $occupation
 * @property string $employer_business_name
 * @property string|null $business_address
 * @property string|null $tel_no
 *
 * @property Tblemployee $emp
 */
class EmployeeSpouseOccupation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_spouse_occupation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'tel_no'], 'string', 'max' => 20],
            [['occupation'], 'string', 'max' => 100],
            [['employer_business_name', 'business_address'], 'string', 'max' => 255],
            [['emp_id', 'occupation', 'employer_business_name'], 'unique', 'targetAttribute' => ['emp_id', 'occupation', 'employer_business_name']],
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
            'emp_id' => 'Emp ID',
            'occupation' => 'Occupation',
            'employer_business_name' => 'Employer/Business Name',
            'business_address' => 'Business Address',
            'tel_no' => 'Telephone No.',
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
