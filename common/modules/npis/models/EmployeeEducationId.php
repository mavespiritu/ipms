<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_educational_attainment_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $level
 * @property string|null $course
 * @property string|null $from_date
 */
class EmployeeEducationId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_educational_attainment_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'from_date'], 'string', 'max' => 20],
            [['level'], 'string', 'max' => 50],
            [['course', 'school'], 'string', 'max' => 255],
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
            'level' => 'Level',
            'course' => 'Course',
            'school' => 'School',
            'from_date' => 'From Date',
        ];
    }
}
