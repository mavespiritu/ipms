<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tblyearly_holiday".
 *
 * @property string $holiday_date
 * @property string|null $holiday_name
 * @property string|null $type
 * @property string|null $holiday_type
 */
class Holiday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblyearly_holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['holiday_date'], 'required'],
            [['holiday_date'], 'safe'],
            [['holiday_name'], 'string', 'max' => 255],
            [['type', 'holiday_type'], 'string', 'max' => 20],
            [['holiday_date'], 'unique'],
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
            'holiday_date' => 'Holiday Date',
            'holiday_name' => 'Holiday Name',
            'type' => 'Type',
            'holiday_type' => 'Holiday Type',
        ];
    }
}
