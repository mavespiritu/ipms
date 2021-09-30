<?php

namespace common\modules\v1\models;

use Yii;

/**
 * This is the model class for table "ppmp_ris_item".
 *
 * @property int $id
 * @property int|null $ris_id
 * @property int|null $ppmp_item_id
 * @property int|null $month_id
 * @property int|null $quantity
 *
 * @property PpmpRis $ris
 * @property PpmpPpmpItem $ppmpItem
 * @property PpmpRisSource[] $ppmpRisSources
 */
class RisItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ppmp_ris_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ris_id', 'ppmp_item_id', 'month_id', 'quantity'], 'integer'],
            [['ris_id'], 'exist', 'skipOnError' => true, 'targetClass' => PpmpRis::className(), 'targetAttribute' => ['ris_id' => 'id']],
            [['ppmp_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PpmpPpmpItem::className(), 'targetAttribute' => ['ppmp_item_id' => 'id']],
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
            'ppmp_item_id' => 'Ppmp Item ID',
            'month_id' => 'Month ID',
            'quantity' => 'Quantity',
        ];
    }

    /**
     * Gets query for [[Ris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRis()
    {
        return $this->hasOne(PpmpRis::className(), ['id' => 'ris_id']);
    }

    /**
     * Gets query for [[PpmpItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpItem()
    {
        return $this->hasOne(PpmpPpmpItem::className(), ['id' => 'ppmp_item_id']);
    }

    /**
     * Gets query for [[PpmpRisSources]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpRisSources()
    {
        return $this->hasMany(PpmpRisSource::className(), ['ris_id' => 'id']);
    }
}
