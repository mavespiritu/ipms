<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tblposition".
 *
 * @property string $position_id
 * @property int|null $rank
 * @property string|null $post_description
 * @property string|null $designation
 *
 * @property TblempPositionItem[] $tblempPositionItems
 * @property Tblemployee[] $tblemployees
 * @property Tbloic[] $tbloics
 * @property TblprlVacancy[] $tblprlVacancies
 */
class Position extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblposition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id'], 'required'],
            [['rank'], 'integer'],
            [['position_id'], 'string', 'max' => 50],
            [['post_description', 'designation'], 'string', 'max' => 255],
            [['position_id'], 'unique'],
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->ipms; // Return the second database connection
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'position_id' => 'Position ID',
            'rank' => 'Rank',
            'post_description' => 'Post Description',
            'designation' => 'Designation',
        ];
    }

    /**
     * Gets query for [[TblempPositionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTblempPositionItems()
    {
        return $this->hasMany(TblempPositionItem::className(), ['position_id' => 'position_id']);
    } */

    /**
     * Gets query for [[Tblemployees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Tblemployee::className(), ['position_id' => 'position_id']);
    }
}
