<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_voluntary_work_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $name_add_org
 * @property string|null $from_date
 */
class EmployeeVoluntaryWorkId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_voluntary_work_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['name_add_org'], 'string', 'max' => 255],
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
            'name_add_org' => 'Name Add Org',
            'from_date' => 'From Date',
        ];
    }
}
