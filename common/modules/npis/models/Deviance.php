<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "tblemp_deviance".
 *
 * @property string $deviance
 * @property string|null $type
 *
 * @property TblempDispAction[] $tblempDispActions
 */
class Deviance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_deviance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deviance'], 'required'],
            [['deviance'], 'string', 'max' => 767],
            [['type'], 'string', 'max' => 100],
            [['deviance'], 'unique'],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'deviance' => 'Deviance',
            'type' => 'Type',
        ];
    }

    /**
     * Gets query for [[TblempDispActions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDisciplinaryActions()
    {
        return $this->hasMany(EmployeeDisciplinaryAction::className(), ['deviance' => 'deviance']);
    }
}
