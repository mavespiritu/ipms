<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "evidence_performance".
 *
 * @property int $id
 * @property int|null $evidence_id
 * @property int|null $ipcr_id
 *
 * @property StaffCompetencyIndicatorEvidence $evidence
 */
class EvidencePerformance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evidence_performance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ipcr_id'], 'required'],
            [['evidence_id', 'ipcr_id'], 'integer'],
            [['evidence_id'], 'exist', 'skipOnError' => true, 'targetClass' => StaffCompetencyIndicatorEvidence::className(), 'targetAttribute' => ['evidence_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'evidence_id' => 'Evidence ID',
            'ipcr_id' => 'Ipcr ID',
        ];
    }

    /**
     * Gets query for [[Evidence]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEvidence()
    {
        return $this->hasOne(StaffCompetencyIndicatorEvidence::className(), ['id' => 'evidence_id']);
    }

    public function getPerformance()
    {
        return $this->hasOne(Ipcr::className(), ['id' => 'ipcr_id']);
    }
}
