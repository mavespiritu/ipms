<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_address".
 *
 * @property string $emp_id
 * @property string $type
 * @property string|null $house_no
 * @property string|null $street
 * @property string|null $subdivision
 * @property string|null $barangay
 * @property string|null $city
 * @property string|null $province
 * @property string|null $zipcode
 *
 * @property Tblemployee $emp
 */
class EmployeeAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barangay', 'city', 'province'], 'required'],
            [['emp_id', 'type', 'zipcode'], 'string', 'max' => 20],
            [['house_no', 'street', 'subdivision', 'barangay', 'city', 'province'], 'string', 'max' => 255],
            [['emp_id', 'type'], 'unique', 'targetAttribute' => ['emp_id', 'type']],
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
            'type' => 'Type',
            'house_no' => 'House No.',
            'street' => 'Street',
            'subdivision' => 'Subdivision/Village',
            'barangay' => 'Barangay',
            'city' => 'City/Municipality',
            'province' => 'Province',
            'zipcode' => 'Zipcode',
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
