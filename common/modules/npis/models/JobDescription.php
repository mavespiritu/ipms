<?php

namespace common\modules\npis\models;

use Yii;

/**
 * This is the model class for table "job_description".
 *
 * @property int $id
 * @property string|null $item_no
 * @property string|null $eligibility
 * @property string|null $education
 * @property string|null $experience
 * @property string|null $training
 * @property string|null $examination
 */
class JobDescription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'job_description';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'reports_to',
                'classification',
                'prescribed_eligibility', 
                'prescribed_education', 
                'prescribed_experience', 
                'prescribed_training', 
                'preferred_eligibility', 
                'preferred_education', 
                'preferred_experience', 
                'preferred_training', 
                'examination',
                'summary',
                'output',
                'responsibility'
            ], 'required'],
            [[
                'reports_to',
                'prescribed_eligibility', 
                'prescribed_education', 
                'prescribed_experience', 
                'prescribed_training', 
                'preferred_eligibility', 
                'preferred_education', 
                'preferred_experience', 
                'preferred_training', 
                'examination',
                'summary',
                'output',
                'responsibility'
            ], 'string'],
            [['item_no'], 'string', 'max' => 50],
            [['classification'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_no' => 'Item No',
            'prescribed_eligibility' => 'Eligibility',
            'prescribed_education' => 'Education',
            'prescribed_experience' => 'Experience',
            'prescribed_training' => 'Training',
            'preferred_eligibility' => 'Eligibility',
            'preferred_education' => 'Education',
            'preferred_experience' => 'Experience',
            'preferred_training' => 'Training',
            'examination' => 'NEDA Exam',
            'summary' => 'Job Summary',
            'output' => 'Job Output',
            'responsibility' => 'Duties and Responsibilities',
        ];
    }
}
