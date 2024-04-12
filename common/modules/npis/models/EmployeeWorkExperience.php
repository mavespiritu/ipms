<?php

namespace common\modules\npis\models;

use Yii;
use yii\helpers\Html;
use common\models\Employee;
/**
 * This is the model class for table "tblemp_work_experience".
 *
 * @property string $emp_id
 * @property string $agency
 * @property string $position
 * @property string $appointment
 * @property string $grade
 * @property string $monthly_salary
 * @property string $date_start
 * @property string|null $date_end
 * @property string|null $type
 * @property string $step
 * @property string|null $approver
 * @property string|null $filename
 * @property string|null $filetempname
 * @property string|null $filetype
 * @property string|null $filesize
 * @property string|null $filepath
 * @property string|null $filename2
 * @property string|null $filetempname2
 * @property string|null $filetype2
 * @property string|null $filesize2
 * @property string|null $filepath2
 * @property string|null $filename3
 * @property string|null $filetempname3
 * @property string|null $filetype3
 * @property string|null $filesize3
 * @property string|null $filepath3
 * @property string|null $filename4
 * @property string|null $filetempname4
 * @property string|null $filetype4
 * @property string|null $filesize4
 * @property string|null $filepath4
 * @property string|null $filename5
 * @property string|null $filetempname5
 * @property string|null $filetype5
 * @property string|null $filesize5
 * @property string|null $filepath5
 * @property string|null $agency_branch
 * @property string|null $remarks
 * @property string|null $rec_type
 * @property string|null $leave_w_o_pay
 * @property string|null $item_no
 *
 * @property Tblemployee $emp
 */
class EmployeeWorkExperience extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemp_work_experience';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_start', 'date_end'], 'safe'],
            [['emp_id', 'appointment', 'type'], 'string', 'max' => 20],
            [['agency', 'filename', 'filesize', 'filename2', 'filesize2', 'filename3', 'filesize3', 'filename4', 'filesize4', 'filename5', 'filesize5', 'remarks', 'leave_w_o_pay'], 'string', 'max' => 255],
            [['position', 'agency_branch'], 'string', 'max' => 100],
            [['grade', 'step'], 'string', 'max' => 10],
            [['monthly_salary'], 'string', 'max' => 30],
            [['approver', 'filetype', 'filetype2', 'filetype3', 'filetype4', 'filetype5', 'rec_type', 'item_no'], 'string', 'max' => 50],
            [['filetempname', 'filetempname2', 'filetempname3', 'filetempname4', 'filetempname5'], 'string', 'max' => 500],
            [['filepath', 'filepath2', 'filepath3', 'filepath4', 'filepath5'], 'string', 'max' => 1000],
            [['emp_id', 'agency', 'position', 'appointment', 'grade', 'monthly_salary', 'date_start', 'step'], 'unique', 'targetAttribute' => ['emp_id', 'agency', 'position', 'appointment', 'grade', 'monthly_salary', 'date_start', 'step']],
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
            'agency' => 'Agency',
            'position' => 'Position',
            'appointment' => 'Appointment',
            'grade' => 'Grade',
            'monthly_salary' => 'Monthly Salary',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'type' => 'Type',
            'step' => 'Step',
            'approver' => 'Approver',
            'filename' => 'Filename',
            'filetempname' => 'Filetempname',
            'filetype' => 'Filetype',
            'filesize' => 'Filesize',
            'filepath' => 'Filepath',
            'filename2' => 'Filename2',
            'filetempname2' => 'Filetempname2',
            'filetype2' => 'Filetype2',
            'filesize2' => 'Filesize2',
            'filepath2' => 'Filepath2',
            'filename3' => 'Filename3',
            'filetempname3' => 'Filetempname3',
            'filetype3' => 'Filetype3',
            'filesize3' => 'Filesize3',
            'filepath3' => 'Filepath3',
            'filename4' => 'Filename4',
            'filetempname4' => 'Filetempname4',
            'filetype4' => 'Filetype4',
            'filesize4' => 'Filesize4',
            'filepath4' => 'Filepath4',
            'filename5' => 'Filename5',
            'filetempname5' => 'Filetempname5',
            'filetype5' => 'Filetype5',
            'filesize5' => 'Filesize5',
            'filepath5' => 'Filepath5',
            'agency_branch' => 'Agency Branch',
            'remarks' => 'Remarks',
            'rec_type' => 'Rec Type',
            'leave_w_o_pay' => 'Leave W O Pay',
            'item_no' => 'Item No',
        ];
    }

    /**
     * Gets query for [[Emp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Tblemployee::className(), ['emp_id' => 'emp_id']);
    }

    public function getId()
    {
        return $this->hasOne(EmployeeWorkExperienceId::className(), [
            'emp_id' => 'emp_id', 
            'agency' => 'agency', 
            'position' => 'position',
            'appointment' => 'appointment',
            'grade' => 'grade',
            'monthly_salary' => 'monthly_salary',
            'date_start' => 'date_start',
            'step' => 'step',
        ]);
    }

    public function getAppointmentPath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/appointment/'.$this->filename.$this->filetempname);
        $path = '';

        $path .= '<table style="width: 100%; table-layout: fixed;" >';

        if(file_exists($filePath) && !is_null($this->filename)){

            $path .= '<tr>';

            $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($this->filename, Yii::getAlias('@web').'/upload_files/appointment/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'</td>';

            $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>&nbsp;</td>' : '';

            $path .= '</tr>';

        }

        if($this->id && $this->id->employeeAppointments){
            foreach($this->id->employeeAppointments as $employeeAppointment){
                if($employeeAppointment->files){
                    foreach($employeeAppointment->files as $file){
                        $path .= '<tr>';

                        $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'</td>';

                        $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>'.(Yii::$app->user->can('pds-work-experience-update') ? Html::a('<i class="fa fa-trash"></i>', '#', [
                            'onClick' => 'deleteWorkExperienceFile('.$file->id.');',
                        ]) : '').'</td>' : '';

                        $path .= '</tr>';
                    }
                }
            }
        }

        $path .= '</table>';

        return $path;
    }

    public function getDutyAssumptionPath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/duty_assumption/'.$this->filename.$this->filetempname);
        $path = '';

        $path .= '<table style="width: 100%; table-layout: fixed;" >';

        if(file_exists($filePath) && !is_null($this->filename)){

            $path .= '<tr>';

            $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($this->filename, Yii::getAlias('@web').'/upload_files/duty_assumption/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'</td>';

            $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>&nbsp;</td>' : '';

            $path .= '</tr>';

        }

        if($this->id && $this->id->employeeDutyAssumptions){
            foreach($this->id->employeeDutyAssumptions as $employeeDutyAssumption){
                if($employeeDutyAssumption->files){
                    foreach($employeeDutyAssumption->files as $file){
                        $path .= '<tr>';

                        $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'</td>';

                        $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>'.(Yii::$app->user->can('pds-work-experience-update') ? Html::a('<i class="fa fa-trash"></i>', '#', [
                            'onClick' => 'deleteWorkExperienceFile('.$file->id.');',
                        ]) : '').'</td>' : '';

                        $path .= '</tr>';
                    }
                }
            }
        }

        $path .= '</table>';

        return $path;
    }

    public function getPositionDescriptionPath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/position_description/'.$this->filename.$this->filetempname);
        $path = '';

        $path .= '<table style="width: 100%; table-layout: fixed;" >';

        if(file_exists($filePath) && !is_null($this->filename)){

            $path .= '<tr>';

            $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($this->filename, Yii::getAlias('@web').'/upload_files/position_description/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'</td>';

            $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>&nbsp;</td>' : '';

            $path .= '</tr>';

        }

        if($this->id && $this->id->employeePositionDescriptions){
            foreach($this->id->employeePositionDescriptions as $employeePositionDescription){
                if($employeePositionDescription->files){
                    foreach($employeePositionDescription->files as $file){
                        $path .= '<tr>';

                        $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'</td>';

                        $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>'.(Yii::$app->user->can('pds-work-experience-update') ? Html::a('<i class="fa fa-trash"></i>', '#', [
                            'onClick' => 'deleteWorkExperienceFile('.$file->id.');',
                        ]) : '').'</td>' : '';

                        $path .= '</tr>';
                    }
                }
            }
        }

        $path .= '</table>';

        return $path;
    }

    public function getOfficeOathPath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/office_oath/'.$this->filename.$this->filetempname);
        $path = '';

        $path .= '<table style="width: 100%; table-layout: fixed;" >';

        if(file_exists($filePath) && !is_null($this->filename)){

            $path .= '<tr>';

            $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($this->filename, Yii::getAlias('@web').'/upload_files/office_oath/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'</td>';

            $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>&nbsp;</td>' : '';

            $path .= '</tr>';

        }

        if($this->id && $this->id->employeeOfficeOaths){
            foreach($this->id->employeeOfficeOaths as $employeeOfficeOath){
                if($employeeOfficeOath->files){
                    foreach($employeeOfficeOath->files as $file){
                        $path .= '<tr>';

                        $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'</td>';

                        $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>'.(Yii::$app->user->can('pds-work-experience-update') ? Html::a('<i class="fa fa-trash"></i>', '#', [
                            'onClick' => 'deleteWorkExperienceFile('.$file->id.');',
                        ]) : '').'</td>' : '';

                        $path .= '</tr>';
                    }
                }
            }
        }

        $path .= '</table>';

        return $path;
    }

    public function getNosaNosiPath()
    {
        // Construct the file path
        $filePath = Yii::getAlias('@frontend/web/upload_files/nosa_nosi/'.$this->filename.$this->filetempname);
        $path = '';

        $path .= '<table style="width: 100%; table-layout: fixed;" >';

        if(file_exists($filePath) && !is_null($this->filename)){

            $path .= '<tr>';

            $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($this->filename, Yii::getAlias('@web').'/upload_files/nosa_nosi/'.$this->filename.$this->filetempname, ['download' => true, 'data-pjax' => 0]).'</td>';

            $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>&nbsp;</td>' : '';

            $path .= '</tr>';

        }

        if($this->id && $this->id->employeeNosaNosis){
            foreach($this->id->employeeNosaNosis as $employeeNosaNosi){
                if($employeeNosaNosi->files){
                    foreach($employeeNosaNosi->files as $file){
                        $path .= '<tr>';

                        $path .= '<td style="width: 80% !important; word-wrap: break-word; vertical-align: top;">'.Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]).'</td>';

                        $path.= Yii::$app->user->can('HR') && ($this->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<td style="vertical-align: top;" align=right>'.(Yii::$app->user->can('pds-work-experience-update') ? Html::a('<i class="fa fa-trash"></i>', '#', [
                            'onClick' => 'deleteWorkExperienceFile('.$file->id.');',
                        ]) : '').'</td>' : '';

                        $path .= '</tr>';
                    }
                }
            }
        }

        $path .= '</table>';

        return $path;
    }
}
