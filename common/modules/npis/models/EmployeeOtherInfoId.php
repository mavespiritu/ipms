<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_other_info_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $type
 * @property string|null $description
 */
class EmployeeOtherInfoId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_other_info_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'description' => 'Description',
        ];
    }
}
