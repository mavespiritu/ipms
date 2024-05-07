<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "training_competency".
 *
 * @property int $id
 * @property int|null $competency_id
 * @property int|null $training_id
 */
class TrainingCompetency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'training_competency';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['competency_id'], 'required'],
            [['competency_id', 'training_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'competency_id' => 'Competency',
            'training_id' => 'Training',
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
     * Gets query for [[Training]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTraining()
    {
        return $this->hasOne(Training::className(), ['id' => 'training_id']);
    }

    /**
     * Gets query for [[Training]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetency()
    {
        return $this->hasOne(Competency::className(), ['comp_id' => 'competency_id']);
    }
}
