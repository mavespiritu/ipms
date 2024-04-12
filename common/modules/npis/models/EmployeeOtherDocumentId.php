<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_other_document_id".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $subject
 * @property string|null $from_date
 */
class EmployeeOtherDocumentId extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_other_document_id';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subject'], 'string'],
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
            'subject' => 'Subject',
            'from_date' => 'From Date',
        ];
    }
}
