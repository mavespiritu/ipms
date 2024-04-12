<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblpis_training_discipline".
 *
 * @property string $discipline
 *
 * @property TblempTrainingProgram[] $tblempTrainingPrograms
 */
class TrainingDiscipline extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblpis_training_discipline';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['discipline'], 'required'],
            [['discipline'], 'string', 'max' => 255],
            [['discipline'], 'unique'],
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
            'discipline' => 'Discipline',
        ];
    }

    /**
     * Gets query for [[TblempTrainingPrograms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeTrainings()
    {
        return $this->hasMany(EmployeeTraining::className(), ['discipline' => 'discipline']);
    }
}
