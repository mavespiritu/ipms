<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_children".
 *
 * @property string $emp_id
 * @property string $child_name
 * @property string|null $birthday
 *
 * @property Tblemployee $emp
 */
class EmployeeChildren extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_children';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['child_name', 'birthday'], 'required'],
            [['birthday'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['child_name'], 'string', 'max' => 255],
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
            'child_name' => 'Name of Child',
            'birthday' => 'Date of Birth',
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
