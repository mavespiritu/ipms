<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Position;
use common\models\Division;

/**
 * This is the model class for table "tblemp_position_item".
 *
 * @property string $item_no
 * @property string|null $position_id
 * @property string|null $division_id
 * @property int|null $grade
 * @property int|null $step
 *
 * @property TblempEmpItem[] $tblempEmpItems
 * @property Tblposition $position
 * @property Tbldivision $division
 */
class EmployeePositionItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_position_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_no', 'position_id', 'division_id', 'grade', 'step', 'status'], 'required'],
            [['grade', 'step', 'status'], 'integer'],
            [['item_no', 'position_id'], 'string', 'max' => 50],
            [['division_id'], 'string', 'max' => 20],
            [['item_no'], 'unique'],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'position_id']],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['division_id' => 'division_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_no' => 'Plantilla Item No',
            'position_id' => 'Position',
            'division_id' => 'Division',
            'grade' => 'Grade',
            'step' => 'Step',
            'status' => 'Status'
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
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['position_id' => 'position_id']);
    }

    /**
     * Gets query for [[Division]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['division_id' => 'division_id']);
    }
}
