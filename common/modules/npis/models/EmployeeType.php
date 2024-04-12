<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemployee_type".
 *
 * @property string $emp_type_id
 * @property string|null $emp_type_description
 *
 * @property Tblemployee[] $tblemployees
 */
class EmployeeType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemployee_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_type_id'], 'required'],
            [['emp_type_id'], 'string', 'max' => 20],
            [['emp_type_description'], 'string', 'max' => 255],
            [['emp_type_id'], 'unique'],
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
            'emp_type_id' => 'Emp Type ID',
            'emp_type_description' => 'Emp Type Description',
        ];
    }

    /**
     * Gets query for [[Tblemployees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['emp_type_id' => 'emp_type_id']);
    }
}
