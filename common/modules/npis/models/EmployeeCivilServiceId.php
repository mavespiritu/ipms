<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_civil_service_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $eligibility
 * @property string|null $exam_date
 */
class EmployeeCivilServiceId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_civil_service_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exam_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['eligibility'], 'string', 'max' => 255],
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
            'eligibility' => 'Eligibility',
            'exam_date' => 'Exam Date',
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
}
