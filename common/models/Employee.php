<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\modules\npis\models\EmployeeType;
use common\modules\npis\models\EmployeeChildren;
use common\modules\npis\models\EmployeeAddress;
use common\modules\npis\models\EmployeeSpouseOccupation;
use common\modules\npis\models\EmployeeEducation;
use common\modules\npis\models\EmployeeCivilService;
use common\modules\npis\models\EmployeeWorkExperience;
use common\modules\npis\models\EmployeeVoluntaryWork;
use common\modules\npis\models\EmployeeTraining;
use common\modules\npis\models\EmployeeOtherInfo;
use common\modules\npis\models\EmployeeQuestion;
use common\modules\npis\models\EmployeeReference;
/**
 * This is the model class for table "tblemployee".
 *
 * @property string $emp_id
 * @property string|null $emp_type_id
 * @property string|null $lname
 * @property string|null $fname
 * @property string|null $mname
 * @property string|null $position_id
 * @property string|null $civil_status
 * @property string|null $birth_place
 * @property string|null $birth_date
 * @property string|null $gender
 * @property string|null $citizenship
 * @property float|null $height
 * @property float|null $weight
 * @property string|null $blood_type
 * @property string|null $cell_no
 * @property string|null $e_mail_add
 * @property string|null $residential_address
 * @property string|null $residential_zip_code
 * @property string|null $residential_tel_no
 * @property string|null $permanent_address
 * @property string|null $permanent_zip_code
 * @property string|null $permanent_tel_no
 * @property string|null $spouse_surname
 * @property string|null $spouse_firstname
 * @property string|null $spouse_middlename
 * @property string|null $father_surname
 * @property string|null $father_firstname
 * @property string|null $father_middlename
 * @property string|null $father_birthday
 * @property string|null $mother_surname
 * @property string|null $mother_firstname
 * @property string|null $mother_middlename
 * @property string|null $mother_birthday
 * @property string|null $hire_date
 * @property string|null $filename
 * @property string|null $picture
 * @property string|null $identification
 * @property string|null $password
 * @property string|null $work_status
 * @property string|null $default_dtr_type
 * @property string|null $division_id
 * @property string|null $one_status
 * @property string|null $earning_credits
 * @property string|null $earning_special
 * @property string|null $nick_name
 * @property string|null $Pag_ibig
 * @property string|null $GSIS
 * @property string|null $TIN
 * @property string|null $Philhealth
 * @property string|null $SSS
 * @property string|null $cedula_number
 * @property float|null $ot_previous_year
 * @property float|null $ot_current_year
 * @property string|null $government_date
 * @property string|null $findex
 * @property string|null $findexL
 * @property string|null $inactivity_date
 * @property string|null $inactivity_reason
 * @property string|null $title
 * @property string|null $prefix
 * @property string|null $staff_detail
 * @property string|null $dtr_exempted
 * @property string|null $sub_division
 *
 * @property Chat[] $chats
 * @property Chat[] $chats0
 * @property TblactualDtr[] $tblactualDtrs
 * @property TblauditTrail[] $tblauditTrails
 * @property TblauditTrail[] $tblauditTrails0
 * @property TblauditTrailPayroll[] $tblauditTrailPayrolls
 * @property Tblaward[] $tblawards
 * @property TblcalMeetings[] $tblcalMeetings
 * @property TblcomAcknowledgement[] $tblcomAcknowledgements
 * @property TblcomEmployeeAssignedCom[] $tblcomEmployeeAssignedComs
 * @property TblcomCommunication[] $titles
 * @property TblcomRouteSlipSender[] $tblcomRouteSlipSenders
 * @property TbldtrEmpLeaveAdditional[] $tbldtrEmpLeaveAdditionals
 * @property TbldtrLeaveApplication[] $tbldtrLeaveApplications
 * @property TblempAddress[] $tblempAddresses
 * @property TblempApprovedOt[] $tblempApprovedOts
 * @property TblempChildren[] $tblempChildrens
 * @property TblempCivilService[] $tblempCivilServices
 * @property TblempDispAction[] $tblempDispActions
 * @property TblempDtrTardyUndertime[] $tblempDtrTardyUndertimes
 * @property TblempDtrType[] $tblempDtrTypes
 * @property TblempDtrTypeDefault[] $tblempDtrTypeDefaults
 * @property TblempDtrTypePm[] $tblempDtrTypePms
 * @property TblempEducationalAttainment[] $tblempEducationalAttainments
 * @property TblempEmpItem[] $tblempEmpItems
 * @property TblempExpiredOt $tblempExpiredOt
 * @property TblempMarriageContract[] $tblempMarriageContracts
 * @property TblempMedicalCertificate[] $tblempMedicalCertificates
 * @property TblempNbiClearance[] $tblempNbiClearances
 * @property TblempNpesRating[] $tblempNpesRatings
 * @property TblempOtherDocument[] $tblempOtherDocuments
 * @property TblempOtherInfo[] $tblempOtherInfos
 * @property TblempQuestions[] $tblempQuestions
 * @property TblempReferences[] $tblempReferences
 * @property TblempServiceContract[] $tblempServiceContracts
 * @property TblempSpecialOrder[] $tblempSpecialOrders
 * @property TblempSpouseOccupation[] $tblempSpouseOccupations
 * @property TblempTrainingProgram[] $tblempTrainingPrograms
 * @property TblempVoluntaryWork[] $tblempVoluntaryWorks
 * @property TblempWorkExperience[] $tblempWorkExperiences
 * @property TblemployeeType $empType
 * @property Tbldivision $subDivision
 * @property Tblposition $position
 * @property Tbldivision $division
 * @property TblformSignatory[] $tblformSignatories
 * @property TblinvLedgerCard[] $tblinvLedgerCards
 * @property TblmanTeamMembership[] $tblmanTeamMemberships
 * @property TblmonthlyCreditTransaction[] $tblmonthlyCreditTransactions
 * @property TblmonthlyDtrSummary[] $tblmonthlyDtrSummaries
 * @property TblnoVerification $tblnoVerification
 * @property Tbloic[] $tbloics
 * @property TblpassSlip[] $tblpassSlips
 * @property TblpdsSetting $tblpdsSetting
 * @property TblprlAddCompDetails[] $tblprlAddCompDetails
 * @property TblprlDivisionEmp[] $tblprlDivisionEmps
 * @property TblprlDivision[] $divisions
 * @property TblprlEmpExemption $tblprlEmpExemption
 * @property TblprlLoanSchedule[] $tblprlLoanSchedules
 * @property TblprlMonthlyPayroll[] $tblprlMonthlyPayrolls
 * @property TblprlPayroll[] $payrollNumbers
 * @property TblprlOccasionalAddComPayroll[] $tblprlOccasionalAddComPayrolls
 * @property TblprlOic[] $tblprlOics
 * @property TblprlOptionalContribution[] $tblprlOptionalContributions
 * @property TblprlPayroll[] $tblprlPayrolls
 * @property TblprlPayrollSignatory[] $tblprlPayrollSignatories
 * @property TblprlYearlyIncomeTax[] $tblprlYearlyIncomeTaxes
 * @property TblrdcInfoModifiers $tblrdcInfoModifiers
 * @property TblstaffUnit[] $tblstaffUnits
 * @property Tblunit[] $unitNames
 * @property TblsystemUser[] $tblsystemUsers
 * @property TbltevAuthapprover $tbltevAuthapprover
 * @property TbltevAuthapprover[] $tbltevAuthapprovers
 * @property TbltevAuthapprover[] $tbltevAuthapprovers0
 * @property TbltevConcernedstaff[] $tbltevConcernedstaff
 * @property TbltevDigitalsignature[] $tbltevDigitalsignatures
 * @property TbltevDrivers $tbltevDrivers
 * @property TbltevTravelorder[] $tbltevTravelorders
 * @property Tbltraining[] $tbltrainings
 * @property TblvehicleTrip[] $tblvehicleTrips
 * @property TblvehicleTrip[] $tblvehicleTrips0
 * @property TblweeklyDtrSummary[] $tblweeklyDtrSummaries
 * @property User[] $users
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblemployee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'civil_status',
                'birth_place',
                'birth_date',
                'gender',
                'citizenship',
                'height',
                'weight',
                'blood_type',
                'cell_no',
                'e_mail_add',
                'residential_address',
                'residential_zip_code',
                'permanent_address',
                'permanent_zip_code',
                'TIN',
                'Pag_ibig',
                'GSIS',
                'Philhealth',
                'SSS',
            ], 'required'],
            [['birth_date', 'father_birthday', 'mother_birthday', 'hire_date', 'government_date', 'inactivity_date'], 'safe'],
            [['height', 'weight', 'ot_previous_year', 'ot_current_year'], 'number'],
            [['picture', 'findex', 'findexL'], 'string'],
            [['emp_id', 'emp_type_id', 'civil_status', 'citizenship', 'cell_no', 'residential_zip_code', 'residential_tel_no', 'permanent_zip_code', 'permanent_tel_no', 'spouse_surname', 'spouse_firstname', 'spouse_middlename', 'father_surname', 'father_firstname', 'father_middlename', 'mother_surname', 'mother_firstname', 'mother_middlename', 'work_status', 'default_dtr_type', 'division_id', 'one_status', 'Pag_ibig', 'GSIS', 'TIN', 'Philhealth', 'SSS', 'cedula_number', 'sub_division'], 'string', 'max' => 20],
            [['lname', 'fname', 'mname', 'position_id', 'nick_name'], 'string', 'max' => 50],
            [['birth_place', 'e_mail_add', 'residential_address', 'permanent_address', 'filename', 'identification', 'password', 'mother_maiden_name'], 'string', 'max' => 255],
            [['gender', 'earning_credits', 'earning_special', 'dtr_exempted'], 'string', 'max' => 10],
            [['blood_type'], 'string', 'max' => 5],
            [['inactivity_reason', 'title', 'prefix', 'staff_detail'], 'string', 'max' => 100],
            [['emp_id'], 'unique'],
            [['emp_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmployeeType::className(), 'targetAttribute' => ['emp_type_id' => 'emp_type_id']],
            [['sub_division'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['sub_division' => 'division_id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Position::className(), 'targetAttribute' => ['position_id' => 'position_id']],
            [['division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['division_id' => 'division_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'emp_id' => 'Emp ID',
            'emp_type_id' => 'Emp Type ID',
            'lname' => 'Lname',
            'fname' => 'Fname',
            'mname' => 'Mname',
            'position_id' => 'Position ID',
            'civil_status' => 'Civil Status',
            'birth_place' => 'Place of Birth',
            'birth_date' => 'Date of Birth',
            'gender' => 'Gender',
            'citizenship' => 'Citizenship',
            'height' => 'Height',
            'weight' => 'Weight',
            'blood_type' => 'Blood Type',
            'cell_no' => 'Mobile No.',
            'e_mail_add' => 'Email Address',
            'residential_address' => 'Residential Address',
            'residential_zip_code' => 'Residential Zip Code',
            'residential_tel_no' => 'Residential Tel No',
            'permanent_address' => 'Permanent Address',
            'permanent_zip_code' => 'Permanent Zip Code',
            'permanent_tel_no' => 'Permanent Tel No',
            'spouse_surname' => 'Spouse Surname',
            'spouse_firstname' => 'Spouse Firstname',
            'spouse_middlename' => 'Spouse Middlename',
            'father_surname' => 'Father Surname',
            'father_firstname' => 'Father Firstname',
            'father_middlename' => 'Father Middlename',
            'father_birthday' => 'Father Birthday',
            'mother_surname' => 'Mother Surname',
            'mother_firstname' => 'Mother Firstname',
            'mother_middlename' => 'Mother Middlename',
            'mother_birthday' => 'Mother Birthday',
            'mother_maiden_name' => 'Mother Maiden Name',
            'hire_date' => 'Hire Date',
            'filename' => 'Filename',
            'picture' => 'Picture',
            'identification' => 'Identification',
            'password' => 'Password',
            'work_status' => 'Work Status',
            'default_dtr_type' => 'Default Dtr Type',
            'division_id' => 'Division ID',
            'one_status' => 'One Status',
            'earning_credits' => 'Earning Credits',
            'earning_special' => 'Earning Special',
            'nick_name' => 'Nick Name',
            'Pag_ibig' => 'PAG IBIG No.',
            'GSIS' => 'GSIS',
            'TIN' => 'TIN',
            'Philhealth' => 'PhilHealth No.',
            'SSS' => 'SSS',
            'cedula_number' => 'Cedula Number',
            'ot_previous_year' => 'Ot Previous Year',
            'ot_current_year' => 'Ot Current Year',
            'government_date' => 'Government Date',
            'findex' => 'Findex',
            'findexL' => 'Findex L',
            'inactivity_date' => 'Inactivity Date',
            'inactivity_reason' => 'Inactivity Reason',
            'title' => 'Title',
            'prefix' => 'Prefix',
            'staff_detail' => 'Staff Detail',
            'dtr_exempted' => 'Dtr Exempted',
            'sub_division' => 'Sub Division',
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->ipms; // Return the second database connection
    }

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    public function getName()
    {
        return $this->mname != '' ? $this->lname.', '.$this->fname.' '.substr($this->mname, 0, 1) .'. ' : $this->lname.', '.$this->fname;
    }

    public static function getList()
    {
        $list = Employee::find()
        ->select([
            'emp_id',
            'concat(lname,", ",fname," ",mname) as name'
        ])
        ->where([
            'work_status' => 'active'
        ])
        ->orderBy([
            'concat(lname,", ",fname," ",mname)' => SORT_ASC
        ])
        ->asArray()
        ->all();

        return ArrayHelper::map($list, 'emp_id', 'name');
    }

    public static function getAllList()
    {
        $list = Employee::find()
        ->select([
            'emp_id',
            'concat(lname,", ",fname," ",mname) as name'
        ])
        ->orderBy([
            'concat(lname,", ",fname," ",mname)' => SORT_ASC
        ])
        ->asArray()
        ->all();

        return ArrayHelper::map($list, 'emp_id', 'name');
    }

    public static function getAllExceptSelfList()
    {
        $list = Employee::find()
        ->select([
            'emp_id',
            'concat(lname,", ",fname," ",mname) as name'
        ])
        ->orderBy([
            'concat(lname,", ",fname," ",mname)' => SORT_ASC
        ])
        ->andWhere(['<>', 'emp_id', Yii::$app->user->identity->userinfo->EMP_N])
        ->asArray()
        ->all();

        return ArrayHelper::map($list, 'emp_id', 'name');
    }

    /**
     * Gets query for [[Chats]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['receiver' => 'emp_id']);
    }

    /**
     * Gets query for [[Chats0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChats0()
    {
        return $this->hasMany(Chat::className(), ['sender' => 'emp_id']);
    }

    /**
     * Gets query for [[TblactualDtrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblactualDtrs()
    {
        return $this->hasMany(TblactualDtr::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblauditTrails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblauditTrails()
    {
        return $this->hasMany(TblauditTrail::className(), ['verifier_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblauditTrails0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblauditTrails0()
    {
        return $this->hasMany(TblauditTrail::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblauditTrailPayrolls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblauditTrailPayrolls()
    {
        return $this->hasMany(TblauditTrailPayroll::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[Tblawards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblawards()
    {
        return $this->hasMany(Tblaward::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblcalMeetings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblcalMeetings()
    {
        return $this->hasMany(TblcalMeetings::className(), ['posted_by' => 'emp_id']);
    }

    /**
     * Gets query for [[TblcomAcknowledgements]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblcomAcknowledgements()
    {
        return $this->hasMany(TblcomAcknowledgement::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblcomEmployeeAssignedComs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblcomEmployeeAssignedComs()
    {
        return $this->hasMany(TblcomEmployeeAssignedCom::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[Titles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTitles()
    {
        return $this->hasMany(TblcomCommunication::className(), ['title' => 'title', 'datetime_encoded' => 'datetime_encoded'])->viaTable('tblcom_employee_assigned_com', ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblcomRouteSlipSenders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblcomRouteSlipSenders()
    {
        return $this->hasMany(TblcomRouteSlipSender::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TbldtrEmpLeaveAdditionals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTbldtrEmpLeaveAdditionals()
    {
        return $this->hasMany(TbldtrEmpLeaveAdditional::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TbldtrLeaveApplications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTbldtrLeaveApplications()
    {
        return $this->hasMany(TbldtrLeaveApplication::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempAddresses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempAddresses()
    {
        return $this->hasMany(TblempAddress::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempApprovedOts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempApprovedOts()
    {
        return $this->hasMany(TblempApprovedOt::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempChildrens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(EmployeeChildren::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempCivilServices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getcivilServices()
    {
        return $this->hasMany(EmployeeCivilService::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempDispActions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempDispActions()
    {
        return $this->hasMany(TblempDispAction::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempDtrTardyUndertimes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempDtrTardyUndertimes()
    {
        return $this->hasMany(TblempDtrTardyUndertime::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempDtrTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempDtrTypes()
    {
        return $this->hasMany(TblempDtrType::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempDtrTypeDefaults]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempDtrTypeDefaults()
    {
        return $this->hasMany(TblempDtrTypeDefault::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempDtrTypePms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempDtrTypePms()
    {
        return $this->hasMany(TblempDtrTypePm::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempEducationalAttainments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducations()
    {
        return $this->hasMany(EmployeeEducation::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempEmpItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempEmpItems()
    {
        return $this->hasMany(TblempEmpItem::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempExpiredOt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempExpiredOt()
    {
        return $this->hasOne(TblempExpiredOt::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempMarriageContracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempMarriageContracts()
    {
        return $this->hasMany(TblempMarriageContract::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempMedicalCertificates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempMedicalCertificates()
    {
        return $this->hasMany(TblempMedicalCertificate::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempNbiClearances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempNbiClearances()
    {
        return $this->hasMany(TblempNbiClearance::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempNpesRatings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempNpesRatings()
    {
        return $this->hasMany(TblempNpesRating::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempOtherDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempOtherDocuments()
    {
        return $this->hasMany(TblempOtherDocument::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempOtherInfos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOtherInformations()
    {
        return $this->hasMany(EmployeeOtherInfo::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempQuestions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(EmployeeQuestion::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempReferences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReferences()
    {
        return $this->hasMany(EmployeeReference::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempServiceContracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempServiceContracts()
    {
        return $this->hasMany(TblempServiceContract::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempSpecialOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTblempSpecialOrders()
    {
        return $this->hasMany(TblempSpecialOrder::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempSpouseOccupations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSpouseOccupation()
    {
        return $this->hasOne(EmployeeSpouseOccupation::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempTrainingPrograms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrainings()
    {
        return $this->hasMany(EmployeeTraining::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempVoluntaryWorks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoluntaryWorks()
    {
        return $this->hasMany(EmployeeVoluntaryWork::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[TblempWorkExperiences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkExperiences()
    {
        return $this->hasMany(EmployeeWorkExperience::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[EmpType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpType()
    {
        return $this->hasOne(TblemployeeType::className(), ['emp_type_id' => 'emp_type_id']);
    }

    /**
     * Gets query for [[SubDivision]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubDivision()
    {
        return $this->hasOne(Tbldivision::className(), ['division_id' => 'sub_division']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Position::className(), ['position_id' => 'position_id']);
    }

    /**
     * Gets query for [[Division]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['division_id' => 'division_id']);
    }

    /**
     * Gets query for [[EmployeeAddress]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(EmployeeAddress::className(), ['emp_id' => 'emp_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['staff_id' => 'emp_id']);
    }
}
