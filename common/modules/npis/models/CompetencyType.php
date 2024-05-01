<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "competency_type".
 *
 * @property string $comp_type
 * @property string|null $competency_type_description
 *
 * @property Competency[] $competencies
 */
class CompetencyType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'competency_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comp_type'], 'required'],
            [['comp_type'], 'string', 'max' => 50],
            [['competency_type_description'], 'string', 'max' => 255],
            [['comp_type'], 'unique'],
        ];
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
            'comp_type' => 'Comp Type',
            'competency_type_description' => 'Competency Type Description',
        ];
    }

    /**
     * Gets query for [[Competencies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetencies()
    {
        return $this->hasMany(Competency::className(), ['comp_type' => 'comp_type']);
    }
}
