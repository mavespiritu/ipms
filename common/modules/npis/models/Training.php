<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "training".
 *
 * @property int $id
 * @property int|null $service_provider_id
 * @property string|null $training_title
 * @property int|null $no_of_hours
 * @property float|null $cost
 * @property string|null $modality
 *
 * @property LearningServiceProvider $serviceProvider
 */
class Training extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'training';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['training_title', 'modality', 'cost', 'service_provider_id', 'no_of_hours'], 'required'],
            [['service_provider_id', 'no_of_hours'], 'integer'],
            [['training_title', 'cost', 'modality'], 'string'],
            [['service_provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => LearningServiceProvider::className(), 'targetAttribute' => ['service_provider_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'service_provider_id' => 'Name of LSP',
            'training_title' => 'Title of Training',
            'no_of_hours' => 'No. of Hours',
            'cost' => 'Cost',
            'modality' => 'Modality',
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
     * Gets query for [[ServiceProvider]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLearningServiceProvider()
    {
        return $this->hasOne(LearningServiceProvider::className(), ['id' => 'service_provider_id']);
    }

    /**
     * Gets query for [[TrainingCompetency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompetencies()
    {
        return $this->hasMany(TrainingCompetency::className(), ['training_id' => 'id']);
    }
}
