<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_ipcr".
 *
 * @property int $id
 * @property string|null $emp_id
 * @property int|null $year
 * @property string|null $semester
 * @property string|null $rating
 */
class Ipcr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_ipcr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'year', 'semester'], 'required'],
            [['year'], 'integer'],
            [['semester', 'approval', 'verified_by'], 'string'],
            [['emp_id', 'rating'], 'string', 'max' => 10],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
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
            'id' => 'ID',
            'emp_id' => 'Name of Staff',
            'year' => 'Year',
            'semester' => 'Semester',
            'rating' => 'Rating',
            'approval' => 'Approval',
            'verified_by' => 'Verified by',
        ];
    }

    /**
     * Gets query for [[Emp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['emp_id' => 'emp_id']);
    }

    public function getVerifier()
    {
        return Employee::findOne(['emp_id' => $this->verified_by]);
    }
}
