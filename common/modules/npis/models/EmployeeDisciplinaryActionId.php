<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_disp_action_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $deviance
 * @property string|null $from_date
 */
class EmployeeDisciplinaryActionId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_disp_action_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviance'], 'string'],
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
            'deviance' => 'Deviance',
            'from_date' => 'From Date',
        ];
    }
}
