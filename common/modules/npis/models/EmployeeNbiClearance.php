<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_nbi_clearance".
 *
 * @property string $emp_id
 * @property string $from_date
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
class EmployeeNbiClearance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_nbi_clearance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'from_date'], 'required'],
            [['from_date'], 'safe'],
            [['emp_id'], 'string', 'max' => 20],
            [['approval'], 'string', 'max' => 10],
            [['approver', 'filetype'], 'string', 'max' => 50],
            [['filename', 'filesize'], 'string', 'max' => 255],
            [['filetempname'], 'string', 'max' => 500],
            [['filepath'], 'string', 'max' => 1000],
            [['emp_id', 'from_date'], 'unique', 'targetAttribute' => ['emp_id', 'from_date']],
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
            'emp_id' => 'Name of Staff',
            'from_date' => 'Date Issued',
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

    /**
     * Gets query for [[Level0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getId()
    {
        return $this->hasOne(EmployeeNbiClearanceId::className(), ['emp_id' => 'emp_id', 'from_date' => 'from_date']);
    }

    public function getFilePath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/nbi_clearance/'.$this->filename.$this->filetempname);
        $path = '';
        // Check if the file exists
        $path .= file_exists($filePath) && !is_null($this->filename) ? Html::a($this->filename, Yii::getAlias('@web').'/upload_files/nbi_clearance/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'<br>' : '';

        if($this->id && $this->id->files){
            foreach($this->id->files as $file){
                $path .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'<br>';
            }
        }
       

        return $path;
    }
}
