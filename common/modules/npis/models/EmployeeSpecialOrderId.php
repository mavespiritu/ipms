<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_special_order_id".
 *
 * @property int $id
 * @property string $emp_id
 * @property string $subject
 * @property string|null $from_date
 */
class EmployeeSpecialOrderId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_special_order_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id'], 'required'],
            [['from_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['subject'], 'string', 'max' => 255],
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
            'subject' => 'Subject',
            'from_date' => 'From Date',
        ];
    }
}
