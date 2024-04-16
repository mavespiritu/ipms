<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_disp_action".
 *
 * @property string $emp_id
 * @property string $deviance
 * @property string $from_date
 * @property string|null $offense
 * @property string|null $approval
 * @property string|null $approver
 * @property string|null $filename
 * @property string|null $filetempname
 * @property string|null $filetype
 * @property string|null $filesize
 * @property string|null $filepath
 *
 * @property Tblemployee $emp
 * @property TblempDeviance $deviance0
 */
class EmployeeDisciplinaryAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_disp_action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'deviance', 'from_date'], 'required'],
            [['from_date'], 'safe'],
            [['emp_id', 'offense'], 'string', 'max' => 20],
            [['deviance'], 'string', 'max' => 767],
            [['approval'], 'string', 'max' => 10],
            [['approver', 'filetype'], 'string', 'max' => 50],
            [['filename', 'filesize'], 'string', 'max' => 255],
            [['filetempname'], 'string', 'max' => 500],
            [['filepath'], 'string', 'max' => 1000],
            [['emp_id', 'deviance', 'from_date'], 'unique', 'targetAttribute' => ['emp_id', 'deviance', 'from_date']],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
            [['deviance'], 'exist', 'skipOnError' => true, 'targetClass' => Deviance::className(), 'targetAttribute' => ['deviance' => 'deviance']],
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
            'deviance' => 'Deviance',
            'from_date' => 'Date Issued',
            'offense' => 'Offense',
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
     * Gets query for [[Deviance0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviance()
    {
        return $this->hasOne(Deviance::className(), ['deviance' => 'deviance']);
    }

    /**
     * Gets query for [[Level0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getId()
    {
        return $this->hasOne(EmployeeDisciplinaryActionId::className(), ['emp_id' => 'emp_id', 'deviance' => 'deviance', 'from_date' => 'from_date']);
    }

    public function getFilePath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/disp_action/'.$this->filename.$this->filetempname);
        $path = '';
        // Check if the file exists
        $path .= file_exists($filePath) && !is_null($this->filename) ? Html::a($this->filename, Yii::getAlias('@web').'/upload_files/disp_action/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'<br>' : '';

        if($this->id && $this->id->files){
            foreach($this->id->files as $file){
                $path .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'<br>';
            }
        }
       

        return $path;
    }
}