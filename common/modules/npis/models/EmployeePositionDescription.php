<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_position_description".
 *
 * @property int $id
 * @property int|null $work_experience_id
 *
 * @property TblempWorkExperienceId $workExperience
 */
class EmployeePositionDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_position_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['work_experience_id'], 'integer'],
            [['work_experience_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeWorkExperienceId::className(), 'targetAttribute' => ['work_experience_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_experience_id' => 'Work Experience ID',
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
     * Gets query for [[WorkExperience]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkExperience()
    {
        return $this->hasOne(EmployeeWorkExperienceId::className(), ['id' => 'work_experience_id']);
    }
}
