<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_other_info".
 *
 * @property string $emp_id
 * @property string $type
 * @property string $description
 * @property string|null $internal_external
 * @property int|null $year
 * @property string|null $approval
 * @property string|null $approver
 * @property string|null $filename
 * @property string|null $filetempname
 * @property string|null $filetype
 * @property string|null $filesize
 * @property string|null $filepath
 * @property string|null $type_detail
 * @property string|null $award_giver
 *
 * @property Tblemployee $emp
 */
class EmployeeOtherInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_other_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required', 'on' => 'staffSkill'],
            [['description', 'year', 'internal_external', 'type_detail', 'award_giver'], 'required', 'on' => 'staffRecognition'],
            [['description'], 'required', 'on' => 'staffOrganization'],
            [['year'], 'integer'],
            [['emp_id', 'internal_external'], 'string', 'max' => 20],
            [['type', 'approver', 'filetype'], 'string', 'max' => 50],
            [['description', 'filename', 'filesize', 'type_detail', 'award_giver'], 'string', 'max' => 255],
            [['approval'], 'string', 'max' => 10],
            [['filetempname'], 'string', 'max' => 500],
            [['filepath'], 'string', 'max' => 1000],
            [['emp_id', 'type', 'description'], 'unique', 'targetAttribute' => ['emp_id', 'type', 'description']],
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
            'type' => 'Type',
            'description' => 'Title',
            'internal_external' => 'Type',
            'year' => 'Year Received',
            'approval' => 'Approval',
            'approver' => 'Approver',
            'filename' => 'Filename',
            'filetempname' => 'Filetempname',
            'filetype' => 'Filetype',
            'filesize' => 'Filesize',
            'filepath' => 'Filepath',
            'type_detail' => 'Frequency/Scope',
            'award_giver' => 'Awarded by',
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
        return $this->hasOne(EmployeeOtherInfoId::className(), ['emp_id' => 'emp_id', 'type' => 'type', 'description' => 'description']);
    }

    public function getFilePath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/destinction_recognition/'.$this->filename.$this->filetempname);
        $path = '';
        // Check if the file exists
        $path .= file_exists($filePath) && !is_null($this->filename) ? Html::a($this->filename, Yii::getAlias('@web').'/upload_files/destinction_recognition/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'<br>' : '';

        if($this->id && $this->id->files){
            foreach($this->id->files as $file){
                $path .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'<br>';
            }
        }
       

        return $path;
    }
}
