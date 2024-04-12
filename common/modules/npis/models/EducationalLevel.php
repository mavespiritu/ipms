<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tbleducational_level".
 *
 * @property string $level
 * @property int|null $ordering
 *
 * @property TblempEducationalAttainment[] $tblempEducationalAttainments
 */
class EducationalLevel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbleducational_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level'], 'required'],
            [['ordering'], 'integer'],
            [['level'], 'string', 'max' => 50],
            [['level'], 'unique'],
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
            'level' => 'Level',
            'ordering' => 'Ordering',
        ];
    }

    /**
     * Gets query for [[TblempEducationalAttainments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployeeEducations()
    {
        return $this->hasMany(EmployeeEducation::className(), ['level' => 'level']);
    }
}
