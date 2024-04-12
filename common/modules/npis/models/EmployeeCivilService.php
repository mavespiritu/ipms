<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;

/**
 * This is the model class for table "tblemp_civil_service".
 *
 * @property string $emp_id
 * @property string $eligibility
 * @property float|null $rating
 * @property string $exam_date
 * @property string|null $exam_place
 * @property string|null $license_number
 * @property string|null $release_date
 * @property string|null $approval
 * @property string|null $approver
 * @property string|null $filename
 * @property string|null $filetempname
 * @property string|null $filetype
 * @property string|null $filesize
 * @property string|null $filepath
 *
 * @property Tblemployee $emp
 */
class EmployeeCivilService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_civil_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'eligibility', 'exam_date', 'exam_place'], 'required'],
            [['rating'], 'number'],
            [['exam_date', 'release_date'], 'safe'],
            [['emp_id', 'license_number'], 'string', 'max' => 20],
            [['eligibility', 'exam_place', 'filename', 'filesize'], 'string', 'max' => 255],
            [['approval'], 'string', 'max' => 10],
            [['approver', 'filetype'], 'string', 'max' => 50],
            [['filetempname'], 'string', 'max' => 500],
            [['filepath'], 'string', 'max' => 1000],
            [['emp_id', 'eligibility', 'exam_date'], 'unique', 'targetAttribute' => ['emp_id', 'eligibility', 'exam_date']],
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
            'eligibility' => 'Title of Eligibility',
            'rating' => 'Rating (if applicable)',
            'exam_date' => 'Date of Examination/Conferment',
            'exam_place' => 'Place of Examination/Conferment',
            'license_number' => 'License Number (if applicable)',
            'release_date' => 'Date of Validity (if applicable)',
            'approval' => 'Approval',
            'approver' => 'Approver',
            'filename' => 'Filename',
            'filetempname' => 'Filetempname',
            'filetype' => 'Filetype',
            'filesize' => 'Filesize',
            'filepath' => 'Filepath',
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

    public function getId()
    {
        return $this->hasOne(EmployeeCivilServiceId::className(), ['emp_id' => 'emp_id', 'eligibility' => 'eligibility', 'exam_date' => 'exam_date']);
    }

    public function getFilePath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/eligibility/'.$this->filename.$this->filetempname);
        $path = '';
        // Check if the file exists
        $path .= file_exists($filePath) && !is_null($this->filename) ? Html::a($this->filename, Yii::getAlias('@web').'/upload_files/eligibility/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'<br>' : '';

        if($this->id && $this->id->files){
            foreach($this->id->files as $file){
                $path .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'<br>';
            }
        }
       

        return $path;
    }
}
