<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblpis_training_category".
 *
 * @property string $category
 *
 * @property TblempTrainingProgram[] $tblempTrainingPrograms
 */
class TrainingCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblpis_training_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['category'], 'string', 'max' => 255],
            [['category'], 'unique'],
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
            'category' => 'Category',
        ];
    }

    /**
     * Gets query for [[TblempTrainingPrograms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeTrainings()
    {
        return $this->hasMany(EmployeeTraining::className(), ['category' => 'category']);
    }
}
