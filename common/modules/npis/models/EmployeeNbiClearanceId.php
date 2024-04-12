<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_nbi_clearance_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $from_date
 */
class EmployeeNbiClearanceId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_nbi_clearance_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
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
            'from_date' => 'From Date',
        ];
    }
}
