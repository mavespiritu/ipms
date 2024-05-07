<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "position_competency_indicator".
 *
 * @property int $id
 * @property string|null $position_id
 * @property int|null $indicator_id
 */
class PositionCompetencyIndicator extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'position_competency_indicator';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['indicator_id'], 'integer'],
            [['position_id'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position_id' => 'Position ID',
            'indicator_id' => 'Indicator ID',
        ];
    }
}
