<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "career_path".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property string|null $position_id
 */
class CareerPath extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'career_path';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id'], 'required'],
            [['emp_id'], 'string', 'max' => 20],
            [['position_id'], 'string', 'max' => 50],
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
            'emp_id' => 'Emp ID',
            'position_id' => 'Position ID',
        ];
    }

    public function getEmployeePositionItem()
    {
        return $this->hasOne(EmployeePositionItem::className(), ['item_no' => 'position_id']);
    }
}
