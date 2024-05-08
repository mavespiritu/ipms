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
            [['eligibility', 'education', 'experience', 'training', 'examination'], 'required'],
            [['eligibility', 'education', 'experience', 'training', 'examination'], 'string'],
            [['item_no'], 'string', 'max' => 50],
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
            'eligibility' => 'Eligibility',
            'education' => 'Education',
            'experience' => 'Experience',
            'training' => 'Training',
            'examination' => 'Examination',
        ];
    }
}
