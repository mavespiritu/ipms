<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_training_program".
 *
 * @property string $emp_id
 * @property string $seminar_title
 * @property string $from_date
 * @property string|null $to_date
 * @property string|null $hours
 * @property string|null $sponsor
 * @property string|null $approval
 * @property string|null $approver
 * @property string|null $filename
 * @property string|null $filetempname
 * @property string|null $filetype
 * @property string|null $filesize
 * @property string|null $filepath
 * @property string|null $participation
 * @property string|null $discipline
 * @property string|null $category
 *
 * @property Tblemployee $emp
 * @property TblpisTrainingDiscipline $discipline0
 * @property TblpisTrainingCategory $category0
 */
class EmployeeTraining extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_training_program';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'seminar_title', 'from_date', 'hours', 'discipline', 'category', 'participation'], 'required'],
            [['from_date', 'to_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['seminar_title', 'filetempname'], 'string', 'max' => 500],
            [['hours', 'approver', 'filetype', 'participation'], 'string', 'max' => 50],
            [['sponsor', 'filename', 'filesize', 'discipline', 'category'], 'string', 'max' => 255],
            [['approval'], 'string', 'max' => 10],
            [['filepath'], 'string', 'max' => 1000],
            [['emp_id', 'seminar_title', 'from_date'], 'unique', 'targetAttribute' => ['emp_id', 'seminar_title', 'from_date']],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
            [['discipline'], 'exist', 'skipOnError' => true, 'targetClass' => TrainingDiscipline::className(), 'targetAttribute' => ['discipline' => 'discipline']],
            [['category'], 'exist', 'skipOnError' => true, 'targetClass' => TrainingCategory::className(), 'targetAttribute' => ['category' => 'category']],
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
            'seminar_title' => 'Title of Learning and Development Interventions / Training programs',
            'from_date' => 'Date From',
            'to_date' => 'Date To',
            'hours' => 'No. of hours',
            'sponsor' => 'Conducted/Sponsored by',
            'approval' => 'Approval',
            'approver' => 'Approver',
            'filename' => 'Filename',
            'filetempname' => 'Filetempname',
            'filetype' => 'Filetype',
            'filesize' => 'Filesize',
            'filepath' => 'Filepath',
            'participation' => 'Participation',
            'discipline' => 'Discipline',
            'category' => 'Type of Learning and Development',
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

    /**
     * Gets query for [[Discipline0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscipline()
    {
        return $this->hasOne(TrainingDiscipline::className(), ['discipline' => 'discipline']);
    }

    /**
     * Gets query for [[Category0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TrainingCategory::className(), ['category' => 'category']);
    }

    public function getId()
    {
        return $this->hasOne(EmployeeTrainingId::className(), ['emp_id' => 'emp_id', 'seminar_title' => 'seminar_title', 'from_date' => 'from_date']);
    }

    public function getFilePath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/training/'.$this->filename.$this->filetempname);
        $path = '';
        // Check if the file exists
        $path .= file_exists($filePath) && !is_null($this->filename) ? Html::a($this->filename, Yii::getAlias('@web').'/upload_files/training/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'<br>' : '';

        if($this->id && $this->id->files){
            foreach($this->id->files as $file){
                $path .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'<br>';
            }
        }
       

        return $path;
    }
}
