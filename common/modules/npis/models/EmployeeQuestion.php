<?php

namespace common\modules\npis\models;

use Yii;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_questions".
 *
 * @property string $emp_id
 * @property int $number
 * @property string $list
 * @property string|null $question
 * @property string|null $answer
 * @property string|null $yes_details
 *
 * @property Tblemployee $emp
 */
class EmployeeQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_questions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer'], 'required'],
            [['number'], 'integer'],
            [['emp_id'], 'string', 'max' => 20],
            [['list'], 'string', 'max' => 10],
            [['question', 'yes_details'], 'string', 'max' => 255],
            [['answer'], 'string', 'max' => 5],
            [['emp_id', 'number', 'list'], 'unique', 'targetAttribute' => ['emp_id', 'number', 'list']],
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
            'emp_id' => 'Emp ID',
            'number' => 'Number',
            'list' => 'List',
            'question' => 'Question',
            'answer' => 'Answer',
            'yes_details' => 'Yes Details',
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
}
