<?php

namespace common\modules\v1\models;

use Yii;

/**
 * This is the model class for table "ppmp_ris_source".
 *
 * @property int $id
 * @property int|null $ris_id
 * @property int|null $ppmp_item_id
 * @property int|null $month_id
 * @property int|null $quantity
 *
 * @property PpmpPpmpItem $ppmpItem
 * @property PpmpRisItem $ris
 */
class RisSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ppmp_ris_source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ris_id', 'ppmp_item_id', 'month_id', 'quantity'], 'integer'],
            [['ppmp_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PpmpItem::className(), 'targetAttribute' => ['ppmp_item_id' => 'id']],
            [['ris_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ris::className(), 'targetAttribute' => ['ris_id' => 'id']],
            [['ris_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => RisItem::className(), 'targetAttribute' => ['ris_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ris_id' => 'Ris ID',
            'ris_item_id' => 'Ris Item ID',
            'ppmp_item_id' => 'Ppmp Item ID',
            'month_id' => 'Month ID',
            'quantity' => 'Quantity',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[PpmpItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpItem()
    {
        return $this->hasOne(PpmpItem::className(), ['id' => 'ppmp_item_id']);
    }

    /**
     * Gets query for [[Ris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRis()
    {
        return $this->hasOne(Ris::className(), ['id' => 'ris_id']);
    }

    /**
     * Gets query for [[Ris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRisItem()
    {
        return $this->hasOne(RisItem::className(), ['id' => 'ris_item_id']);
    }
}
