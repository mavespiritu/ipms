<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "learning_service_provider".
 *
 * @property int $id
 * @property string|null $lsp_name
 * @property string|null $address
 * @property string|null $contact_no
 *
 * @property Training[] $trainings
 */
class LearningServiceProvider extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'learning_service_provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lsp_name', 'address', 'contact_no', 'specialization'], 'required'],
            [['lsp_name', 'address', 'contact_no'], 'string'],
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
            'id' => 'ID',
            'lsp_name' => 'Name of LSP',
            'address' => 'Address',
            'contact_no' => 'Contact No',
            'specialization' => 'Specialization'
        ];
    }

    /**
     * Gets query for [[Trainings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrainings()
    {
        return $this->hasMany(Training::className(), ['service_provider_id' => 'id']);
    }
}
