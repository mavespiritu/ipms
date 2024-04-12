<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_voluntary_work".
 *
 * @property string $emp_id
 * @property string $name_add_org
 * @property string $from_date
 * @property string|null $to_date
 * @property float|null $hours
 * @property string|null $nature_of_work
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
class EmployeeVoluntaryWork extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_voluntary_work';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'name_add_org', 'from_date'], 'required'],
            [['from_date', 'to_date'], 'safe'],
            [['hours'], 'number'],
            [['emp_id'], 'string', 'max' => 20],
            [['name_add_org', 'nature_of_work', 'filename', 'filesize'], 'string', 'max' => 255],
            [['approval'], 'string', 'max' => 10],
            [['approver', 'filetype'], 'string', 'max' => 50],
            [['filetempname'], 'string', 'max' => 500],
            [['filepath'], 'string', 'max' => 1000],
            [['emp_id', 'name_add_org', 'from_date'], 'unique', 'targetAttribute' => ['emp_id', 'name_add_org', 'from_date']],
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
            'name_add_org' => 'Name and Address of Organization',
            'from_date' => 'Date From',
            'to_date' => 'Date To',
            'hours' => 'No. of hours',
            'nature_of_work' => 'Position / Nature Of Work',
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
        return $this->hasOne(EmployeeVoluntaryWorkId::className(), ['emp_id' => 'emp_id', 'name_add_org' => 'name_add_org', 'from_date' => 'from_date']);
    }

    public function getFilePath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/voluntary_work/'.$this->filename.$this->filetempname);
        $path = '';
        // Check if the file exists
        $path .= file_exists($filePath) && !is_null($this->filename) ? Html::a($this->filename, Yii::getAlias('@web').'/upload_files/voluntary_work/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'<br>' : '';

        if($this->id && $this->id->files){
            foreach($this->id->files as $file){
                $path .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'<br>';
            }
        }
       

        return $path;
    }
}
