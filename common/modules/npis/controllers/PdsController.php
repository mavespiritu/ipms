<?php

namespace common\modules\npis\controllers;

use Yii;
use common\models\Employee;
use common\modules\npis\models\EmployeeAddress;
use common\modules\npis\models\EmployeeSpouseOccupation;
use common\modules\npis\models\EmployeeEducation;
use common\modules\npis\models\EmployeeEducationId;
use common\modules\npis\models\EmployeeCivilService;
use common\modules\npis\models\EmployeeCivilServiceId;
use common\modules\npis\models\EmployeeChildren;
use common\modules\npis\models\EmployeeWorkExperience;
use common\modules\npis\models\EmployeeWorkExperienceId;
use common\modules\npis\models\EmployeeAppointment;
use common\modules\npis\models\EmployeeDutyAssumption;
use common\modules\npis\models\EmployeePositionDescription;
use common\modules\npis\models\EmployeeOfficeOath;
use common\modules\npis\models\EmployeeNosaNosi;
use common\modules\npis\models\EmployeeVoluntaryWork;
use common\modules\npis\models\EmployeeVoluntaryWorkId;
use common\modules\npis\models\EmployeeTraining;
use common\modules\npis\models\EmployeeTrainingId;
use common\modules\npis\models\EmployeeOtherInfo;
use common\modules\npis\models\EmployeeOtherInfoId;
use common\modules\npis\models\EmployeeQuestion;
use common\modules\npis\models\EmployeeReference;
use common\modules\npis\models\EducationalLevel;
use common\modules\npis\models\TrainingCategory;
use common\modules\npis\models\TrainingDiscipline;
use common\modules\npis\models\Ipcr;
use common\modules\npis\models\IpcrSearch;
use markavespiritu\user\models\User;
use markavespiritu\user\models\UserInfo;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class PdsController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['pds-index'],
                    ],
                    [
                        'actions' => ['view', 'scope-list'],
                        'allow' => true,
                        'roles' => ['pds-view'],
                    ],
                    [
                        'actions' => ['view-personal-information'],
                        'allow' => true,
                        'roles' => ['pds-personal-information-view'],
                    ],
                    [
                        'actions' => ['update-personal-information'],
                        'allow' => true,
                        'roles' => ['pds-personal-information-update'],
                    ],
                    [
                        'actions' => ['view-family-background'],
                        'allow' => true,
                        'roles' => ['pds-family-background-view'],
                    ],
                    [
                        'actions' => ['update-family-background'],
                        'allow' => true,
                        'roles' => ['pds-family-background-update'],
                    ],
                    [
                        'actions' => ['create-child'],
                        'allow' => true,
                        'roles' => ['pds-child-create'],
                    ],
                    [
                        'actions' => ['update-child'],
                        'allow' => true,
                        'roles' => ['pds-child-update'],
                    ],
                    [
                        'actions' => ['delete-child'],
                        'allow' => true,
                        'roles' => ['pds-child-delete'],
                    ],
                    [
                        'actions' => ['view-educational-background'],
                        'allow' => true,
                        'roles' => ['pds-educational-background-view'],
                    ],
                    [
                        'actions' => ['create-education'],
                        'allow' => true,
                        'roles' => ['pds-education-create'],
                    ],
                    [
                        'actions' => ['update-education'],
                        'allow' => true,
                        'roles' => ['pds-education-update'],
                    ],
                    [
                        'actions' => ['delete-education'],
                        'allow' => true,
                        'roles' => ['pds-education-delete'],
                    ],
                    [
                        'actions' => ['approve-education'],
                        'allow' => true,
                        'roles' => ['pds-education-approve'],
                    ],
                    [
                        'actions' => ['view-eligibility'],
                        'allow' => true,
                        'roles' => ['pds-eligibility-view'],
                    ],
                    [
                        'actions' => ['create-eligibility'],
                        'allow' => true,
                        'roles' => ['pds-eligibility-create'],
                    ],
                    [
                        'actions' => ['update-eligibility'],
                        'allow' => true,
                        'roles' => ['pds-eligibility-update'],
                    ],
                    [
                        'actions' => ['delete-eligibility'],
                        'allow' => true,
                        'roles' => ['pds-eligibility-delete'],
                    ],
                    [
                        'actions' => ['approve-eligibility'],
                        'allow' => true,
                        'roles' => ['pds-eligibility-approve'],
                    ],
                    [
                        'actions' => ['view-work-experience'],
                        'allow' => true,
                        'roles' => ['pds-work-experience-view'],
                    ],
                    [
                        'actions' => ['create-work-experience'],
                        'allow' => true,
                        'roles' => ['pds-work-experience-create'],
                    ],
                    [
                        'actions' => ['update-appointment', 'update-duty-assumption', 'update-position-description', 'update-office-oath', 'update-nosa-nosi'],
                        'allow' => true,
                        'roles' => ['pds-work-experience-update'],
                    ],
                    [
                        'actions' => ['delete-work-experience'],
                        'allow' => true,
                        'roles' => ['pds-work-experience-delete'],
                    ],
                    [
                        'actions' => ['view-voluntary-work'],
                        'allow' => true,
                        'roles' => ['pds-voluntary-work-view'],
                    ],
                    [
                        'actions' => ['create-voluntary-work'],
                        'allow' => true,
                        'roles' => ['pds-voluntary-work-create'],
                    ],
                    [
                        'actions' => ['update-voluntary-work'],
                        'allow' => true,
                        'roles' => ['pds-voluntary-work-update'],
                    ],
                    [
                        'actions' => ['delete-voluntary-work'],
                        'allow' => true,
                        'roles' => ['pds-voluntary-work-delete'],
                    ],
                    [
                        'actions' => ['approve-voluntary-work'],
                        'allow' => true,
                        'roles' => ['pds-voluntary-work-approve'],
                    ],
                    [
                        'actions' => ['view-training'],
                        'allow' => true,
                        'roles' => ['pds-training-view'],
                    ],
                    [
                        'actions' => ['create-training'],
                        'allow' => true,
                        'roles' => ['pds-training-create'],
                    ],
                    [
                        'actions' => ['update-training'],
                        'allow' => true,
                        'roles' => ['pds-training-update'],
                    ],
                    [
                        'actions' => ['delete-training'],
                        'allow' => true,
                        'roles' => ['pds-training-delete'],
                    ],
                    [
                        'actions' => ['approve-training'],
                        'allow' => true,
                        'roles' => ['pds-training-approve'],
                    ],
                    [
                        'actions' => ['view-other-information'],
                        'allow' => true,
                        'roles' => ['pds-other-information-view'],
                    ],
                    [
                        'actions' => ['view-skill'],
                        'allow' => true,
                        'roles' => ['pds-skill-view'],
                    ],
                    [
                        'actions' => ['create-skill'],
                        'allow' => true,
                        'roles' => ['pds-skill-create'],
                    ],
                    [
                        'actions' => ['update-skill'],
                        'allow' => true,
                        'roles' => ['pds-skill-update'],
                    ],
                    [
                        'actions' => ['delete-skill'],
                        'allow' => true,
                        'roles' => ['pds-skill-delete'],
                    ],
                    [
                        'actions' => ['approve-skill'],
                        'allow' => true,
                        'roles' => ['pds-skill-approve'],
                    ],
                    [
                        'actions' => ['view-recognition'],
                        'allow' => true,
                        'roles' => ['pds-recognition-view'],
                    ],
                    [
                        'actions' => ['create-recognition'],
                        'allow' => true,
                        'roles' => ['pds-recognition-create'],
                    ],
                    [
                        'actions' => ['update-recognition'],
                        'allow' => true,
                        'roles' => ['pds-recognition-update'],
                    ],
                    [
                        'actions' => ['delete-recognition'],
                        'allow' => true,
                        'roles' => ['pds-recognition-delete'],
                    ],
                    [
                        'actions' => ['approve-recognition'],
                        'allow' => true,
                        'roles' => ['pds-recognition-approve'],
                    ],
                    [
                        'actions' => ['view-organization'],
                        'allow' => true,
                        'roles' => ['pds-organization-view'],
                    ],
                    [
                        'actions' => ['create-organization'],
                        'allow' => true,
                        'roles' => ['pds-organization-create'],
                    ],
                    [
                        'actions' => ['update-organization'],
                        'allow' => true,
                        'roles' => ['pds-organization-update'],
                    ],
                    [
                        'actions' => ['delete-organization'],
                        'allow' => true,
                        'roles' => ['pds-organization-delete'],
                    ],
                    [
                        'actions' => ['approve-organization'],
                        'allow' => true,
                        'roles' => ['pds-organization-approve'],
                    ],
                    [
                        'actions' => ['view-question'],
                        'allow' => true,
                        'roles' => ['pds-question-view'],
                    ],
                    [
                        'actions' => ['view-reference'],
                        'allow' => true,
                        'roles' => ['pds-reference-view'],
                    ],
                    [
                        'actions' => ['create-reference'],
                        'allow' => true,
                        'roles' => ['pds-reference-create'],
                    ],
                    [
                        'actions' => ['update-reference'],
                        'allow' => true,
                        'roles' => ['pds-reference-update'],
                    ],
                    [
                        'actions' => ['delete-reference'],
                        'allow' => true,
                        'roles' => ['pds-reference-delete'],
                    ],
                    [
                        'actions' => ['print'],
                        'allow' => true,
                        'roles' => ['pds-print'],
                    ],
                    [
                        'actions' => ['excel'],
                        'allow' => true,
                        'roles' => ['pds-excel'],
                    ],
                    [
                        'actions' => ['view-staff-profile'],
                        'allow' => true,
                        'roles' => ['pds-staff-profile-view'],
                    ],
                    [
                        'actions' => ['notify'],
                        'allow' => true,
                        'roles' => ['pds-notify'],
                    ],
                    [
                        'actions' => ['review'],
                        'allow' => true,
                        'roles' => ['pds-review'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Employee();

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionViewStaffProfile($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        Yii::$app->session->remove('selectedStaffProfile');
        Yii::$app->session->remove('staffPdsLastTab');

        if(!Yii::$app->user->can('HR')){
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->renderAjax('_staff', [
            'model' => $model
        ]);
    }

    public function actionView()
    {
        $model = Employee::findOne(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionViewPersonalInformation($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        return $this->renderAjax('personal-information', [
            'model' => $model
        ]);
    }

    public function actionUpdatePersonalInformation($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $permanentAddressModel = EmployeeAddress::findOne([
            'emp_id' => $model->emp_id,
            'type' => 'permanent'
        ]) ? EmployeeAddress::findOne([
            'emp_id' => $model->emp_id,
            'type' => 'permanent'
        ]) : new EmployeeAddress();

        $permanentAddressModel->emp_id = $model->emp_id;
        $permanentAddressModel->type = 'permanent';

        $residentialAddressModel = EmployeeAddress::findOne([
            'emp_id' => $model->emp_id,
            'type' => 'residential'
        ]) ? EmployeeAddress::findOne([
            'emp_id' => $model->emp_id,
            'type' => 'residential'
        ]) : new EmployeeAddress();

        $residentialAddressModel->emp_id = $model->emp_id;
        $residentialAddressModel->type = 'residential';

        $addressModels = [];
        $addressModels[] = $residentialAddressModel;
        $addressModels[] = $permanentAddressModel;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $model->load(Yii::$app->request->post())
        ){
            $addresses = Yii::$app->request->post('EmployeeAddress');

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($model->save()){
                    if(!empty($addresses)){
                        foreach($addresses as $addressModel){
                            $address = EmployeeAddress::findOne([
                                'emp_id' => $model->emp_id,
                                'type' => $addressModel['type']
                            ]) ? EmployeeAddress::findOne([
                                'emp_id' => $model->emp_id,
                                'type' => $addressModel['type']
                            ]) : new EmployeeAddress();
                    
                            $address->emp_id = $model->emp_id;
                            $address->type = $addressModel['type'];
                            $address->house_no = $addressModel['house_no'];
                            $address->street = $addressModel['street'];
                            $address->subdivision = $addressModel['subdivision'];
                            $address->barangay = $addressModel['barangay'];
                            $address->city = $addressModel['city'];
                            $address->province = $addressModel['province'];
                            $address->save();
                        }
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Personal information has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving personal information');
            }
        }

        return $this->renderAjax('_personal-information-form', [
            'model' => $model,
            'addressModels' => $addressModels,
        ]);
    }

    public function actionViewFamilyBackground($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        return $this->renderAjax('family-background', [
            'model' => $model
        ]);
    }

    public function actionUpdateFamilyBackground($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $spouseOccupationModel = EmployeeSpouseOccupation::findOne([
            'emp_id' => $model->emp_id,
        ]) ? EmployeeSpouseOccupation::findOne([
            'emp_id' => $model->emp_id,
        ]) : new EmployeeSpouseOccupation();

        $spouseOccupationModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $model->load(Yii::$app->request->post()) &&
            $spouseOccupationModel->load(Yii::$app->request->post())
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($model->save()){
                    $spouseOccupationModel->save();
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Family background has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving family background');
            }
        }

        return $this->renderAjax('_family-background-form', [
            'model' => $model,
            'spouseOccupationModel' => $spouseOccupationModel,
        ]);
    }

    public function actionCreateChild($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $childModel = new EmployeeChildren();
        $childModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if($childModel->load(Yii::$app->request->post())){
           
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($childModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Child information has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving child information');
            }
        }

        return $this->renderAjax('_child-form', [
            'model' => $model,
            'childModel' => $childModel
        ]);
    }

    public function actionUpdateChild($emp_id, $child_name)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $childModel = EmployeeChildren::findOne(['emp_id' => $emp_id, 'child_name' => $child_name]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if($childModel->load(Yii::$app->request->post())){
           
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($childModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Child information has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving child information');
            }
        }

        return $this->renderAjax('_child-form', [
            'model' => $model,
            'childModel' => $childModel
        ]);
    }

    public function actionDeleteChild()
    {
        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post();
            $model = Employee::findOne(['emp_id' => $postData['emp_id']]);

            $childModel = EmployeeChildren::findOne(['emp_id' => $postData['emp_id'], 'child_name' => $postData['child_name']]);

            if(!Yii::$app->user->can('HR')){
                if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                    throw new NotFoundHttpException('The requested page does not exist.');
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($childModel->delete()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Child information has been deleted successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while deleting child information');
            }
        }
    }

    public function actionViewEducationalBackground($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeEducation::find()
                ->leftJoin('tbleducational_level', 'tbleducational_level.level = tblemp_educational_attainment.level')
                ->where(['emp_id' => $model->emp_id])
                ->orderBy([
                    'tbleducational_level.ordering' => SORT_ASC,
                    'from_date' => SORT_ASC
                ]),
        ]);

        $educations = EmployeeEducation::find()
        ->leftJoin('tbleducational_level', 'tbleducational_level.level = tblemp_educational_attainment.level')
        ->where(['emp_id' => $model->emp_id])
        ->orderBy([
            'tbleducational_level.ordering' => SORT_ASC,
            'from_date' => SORT_ASC
        ])
        ->all();
        
        $educationModels = [];

        if(!empty($educations))
        {
            foreach($educations as $idx => $education)
            {
                $educationModels[$idx + 1] = $education;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeEducation');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedEducations = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedEducations[] = $educationModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedEducations)){
                    foreach($selectedEducations as $selectedEducation){
                        $educationId = EmployeeEducationId::findOne([
                            'emp_id' => $selectedEducation->emp_id,
                            'level' => $selectedEducation->level,
                            'course' => $selectedEducation->course,
                            'school' => $selectedEducation->school,
                            'from_date' => $selectedEducation->from_date,
                        ]);

                        if($educationId){
                            $educationId->delete();
                        }
                        $selectedEducation->delete();
                    }

                    $transaction->commit();
                    return [
                        'success' => 'Education information has been deleted successfully',
                        'indexes' => $selectedIndexes
                    ];
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting education information'];
            }
        }

        return $this->renderAjax('educational-background', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'educationModels' => $educationModels,
        ]);
    }

    public function actionCreateEducation($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $educationModel = new EmployeeEducation();
        $educationModel->emp_id = $model->emp_id;
        $educationModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $educationModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeEducationId();
        $idModel->emp_id = $model->emp_id;

        $levels = EducationalLevel::find()->orderBy(['ordering' => SORT_ASC])->all();
        $levels = ArrayHelper::map($levels, 'level', 'level');

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $educationModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->level = $educationModel->level;
                $idModel->course = $educationModel->course;
                $idModel->school = $educationModel->school;
                $idModel->from_date = $educationModel->from_date;
                $idModel->save();
                if($educationModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Education information has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving education information');
            }
        }

        return $this->renderAjax('_education-form', [
            'model' => $model,
            'educationModel' => $educationModel,
            'idModel' => $idModel,
            'levels' => $levels,
            'idx' => 0,
        ]);
    }

    public function actionUpdateEducation($emp_id, $level, $course, $school, $from_date, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $educationModel = EmployeeEducation::findOne([
            'emp_id' => $emp_id,
            'level' => $level,
            'course' => $course,
            'school' => $school,
            'from_date' => $from_date,
        ]) ? EmployeeEducation::findOne([
            'emp_id' => $emp_id,
            'level' => $level,
            'course' => $course,
            'school' => $school,
            'from_date' => $from_date,
        ]) : new EmployeeEducation();

        $educationModel->emp_id = $model->emp_id;
        $educationModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $educationModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeEducationId::findOne([
            'emp_id' => $emp_id,
            'level' => $level,
            'course' => $course,
            'school' => $school,
            'from_date' => $from_date,
        ]) ? EmployeeEducationId::findOne([
            'emp_id' => $emp_id,
            'level' => $level,
            'course' => $course,
            'school' => $school,
            'from_date' => $from_date,
        ]) : new EmployeeEducationId();

        $idModel->emp_id = $model->emp_id;

        $levels = EducationalLevel::find()->orderBy(['ordering' => SORT_ASC])->all();
        $levels = ArrayHelper::map($levels, 'level', 'level');

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $educationModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->level = $educationModel->level;
                $idModel->course = $educationModel->course;
                $idModel->school = $educationModel->school;
                $idModel->from_date = $educationModel->from_date;
                $idModel->save();
                if($educationModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Education information has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving education information');
            }
        }

        return $this->renderAjax('_education-form', [
            'model' => $model,
            'educationModel' => $educationModel,
            'idModel' => $idModel,
            'levels' => $levels,
            'idx' => $idx
        ]);
    }

    public function actionApproveEducation($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $educations = EmployeeEducation::find()
            ->leftJoin('tbleducational_level', 'tbleducational_level.level = tblemp_educational_attainment.level')
            ->where(['emp_id' => $model->emp_id])
            ->orderBy([
                'tbleducational_level.ordering' => SORT_ASC,
                'from_date' => SORT_ASC
            ])
            ->all();
            
            $educationModels = [];

            if(!empty($educations))
            {
                foreach($educations as $idx => $education)
                {
                    $educationModels[$idx + 1] = $education;
                }
            }

            $postData = Yii::$app->request->post('EmployeeEducation');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedEducations = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedEducations[] = $educationModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if(!empty($selectedEducations)){
                    foreach($selectedEducations as $selectedEducation){
                        $selectedEducation->approval = 'yes';
                        $selectedEducation->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedEducation->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'educational background',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in educational background have been approved by the HR Unit');

                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Education information has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving education information');
            }
        }
    }

    public function actionViewEligibility($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeCivilService::find()
                ->where(['emp_id' => $model->emp_id])
                ->orderBy([
                    'exam_date' => SORT_DESC
                ]),
        ]);

        $eligibilities = EmployeeCivilService::find()
            ->where(['emp_id' => $model->emp_id])
            ->orderBy([
                'exam_date' => SORT_DESC
            ])
            ->all();
        
        $eligibilityModels = [];

        if(!empty($eligibilities))
        {
            foreach($eligibilities as $idx => $eligibility)
            {
                $eligibilityModels[$idx + 1] = $eligibility;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeCivilService');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedEligibilities = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedEligibilities[] = $eligibilityModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedEligibilities)){
                    foreach($selectedEligibilities as $selectedEligibility){
                        $eligibilityId = EmployeeCivilServiceId::findOne([
                            'emp_id' => $selectedEligibility->emp_id,
                            'eligibility' => $selectedEligibility->eligibility,
                            'exam_date' => $selectedEligibility->exam_date,
                        ]);

                        if($eligibilityId){
                            $eligibilityId->delete();
                        }
                        $selectedEligibility->delete();
                    }

                    $transaction->commit();
                    return [
                        'success' => 'Civil service eligibility has been deleted successfully',
                        'indexes' => $selectedIndexes
                    ];

                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting civil service eligibility'];
            }
        }

        return $this->renderAjax('eligibility', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'eligibilityModels' => $eligibilityModels,
        ]);
    }

    public function actionCreateEligibility($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $eligibilityModel = new EmployeeCivilService();
        $eligibilityModel->emp_id = $model->emp_id;
        $eligibilityModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $eligibilityModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeCivilServiceId();
        $idModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $eligibilityModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->eligibility = $eligibilityModel->eligibility;
                $idModel->exam_date = $eligibilityModel->exam_date;
                $idModel->save();
                if($eligibilityModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Civil service eligibility has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving civil service eligibility');
            }
        }

        return $this->renderAjax('_eligibility-form', [
            'model' => $model,
            'eligibilityModel' => $eligibilityModel,
            'idModel' => $idModel,
            'idx' => 0,
        ]);
    }

    public function actionUpdateEligibility($emp_id, $eligibility, $exam_date, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $eligibilityModel = EmployeeCivilService::findOne([
            'emp_id' => $emp_id,
            'eligibility' => $eligibility,
            'exam_date' => $exam_date,
        ]) ? EmployeeCivilService::findOne([
            'emp_id' => $emp_id,
            'eligibility' => $eligibility,
            'exam_date' => $exam_date,
        ]) : new EmployeeCivilService();

        $eligibilityModel->emp_id = $model->emp_id;
        $eligibilityModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $eligibilityModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeCivilServiceId::findOne([
            'emp_id' => $emp_id,
            'eligibility' => $eligibility,
            'exam_date' => $exam_date,
        ]) ? EmployeeCivilServiceId::findOne([
            'emp_id' => $emp_id,
            'eligibility' => $eligibility,
            'exam_date' => $exam_date,
        ]) : new EmployeeCivilServiceId();

        $idModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $eligibilityModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->eligibility = $eligibilityModel->eligibility;
                $idModel->exam_date = $eligibilityModel->exam_date;
                $idModel->save();
                if($eligibilityModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Civil service eligibility has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving civil service eligibility');
            }
        }

        return $this->renderAjax('_eligibility-form', [
            'model' => $model,
            'eligibilityModel' => $eligibilityModel,
            'idModel' => $idModel,
            'idx' => $idx
        ]);
    }

    public function actionApproveEligibility($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $eligibilities = EmployeeCivilService::find()
            ->where(['emp_id' => $model->emp_id])
            ->orderBy([
                'exam_date' => SORT_DESC
            ])
            ->all();
            
            $eligibilityModels = [];

            if(!empty($eligibilities))
            {
                foreach($eligibilities as $idx => $eligibility)
                {
                    $eligibilityModels[$idx + 1] = $eligibility;
                }
            }

            $postData = Yii::$app->request->post('EmployeeCivilService');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedEligibilities = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedEligibilities[] = $eligibilityModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedEligibilities)){
                    foreach($selectedEligibilities as $selectedEligibility){
                        $selectedEligibility->approval = 'yes';
                        $selectedEligibility->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedEligibility->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'civil service eligibility',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in civil service eligibility have been approved by the HR Unit');

                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Civil service eligibility has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving civil service eligibility');
            }
        }
    }

    public function actionViewWorkExperience($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeWorkExperience::find()
                ->where(['emp_id' => $model->emp_id])
                ->orderBy([
                    'date_start' => SORT_DESC
                ]),
        ]);

        $workExperiences = EmployeeWorkExperience::find()
        ->where(['emp_id' => $model->emp_id])
        ->orderBy([
            'date_start' => SORT_DESC
        ])
        ->all();

        if(!empty($workExperiences)){
            foreach($workExperiences as $workExperience){
                $idModel = EmployeeWorkExperienceId::findOne([
                    'emp_id' => $workExperience->emp_id,
                    'agency' => $workExperience->agency,
                    'position' => $workExperience->position,
                    'appointment' => $workExperience->appointment,
                    'grade' => $workExperience->grade,
                    'monthly_salary' => $workExperience->monthly_salary,
                    'date_start' => $workExperience->date_start,
                    'step' => $workExperience->step,
                ]) ? EmployeeWorkExperienceId::findOne([
                    'emp_id' => $workExperience->emp_id,
                    'agency' => $workExperience->agency,
                    'position' => $workExperience->position,
                    'appointment' => $workExperience->appointment,
                    'grade' => $workExperience->grade,
                    'monthly_salary' => $workExperience->monthly_salary,
                    'date_start' => $workExperience->date_start,
                    'step' => $workExperience->step,
                ]) : new EmployeeWorkExperienceId();

                $idModel->emp_id = $workExperience->emp_id;
                $idModel->agency = $workExperience->agency;
                $idModel->position = $workExperience->position;
                $idModel->appointment = $workExperience->appointment;
                $idModel->grade = $workExperience->grade;
                $idModel->monthly_salary = $workExperience->monthly_salary;
                $idModel->date_start = $workExperience->date_start;
                $idModel->step = $workExperience->step;
                if($idModel->save()){
                    $appointmentModel = EmployeeAppointment::findOne(['work_experience_id' => $idModel->id]) ? EmployeeAppointment::findOne(['work_experience_id' => $idModel->id]) : new EmployeeAppointment();
                    $appointmentModel->work_experience_id = $idModel->id;
                    $appointmentModel->save();

                    $dutyAssumptionModel = EmployeeDutyAssumption::findOne(['work_experience_id' => $idModel->id]) ? EmployeeDutyAssumption::findOne(['work_experience_id' => $idModel->id]) : new EmployeeDutyAssumption();
                    $dutyAssumptionModel->work_experience_id = $idModel->id;
                    $dutyAssumptionModel->save();

                    $positionDescriptionModel = EmployeePositionDescription::findOne(['work_experience_id' => $idModel->id]) ? EmployeePositionDescription::findOne(['work_experience_id' => $idModel->id]) : new EmployeePositionDescription();
                    $positionDescriptionModel->work_experience_id = $idModel->id;
                    $positionDescriptionModel->save();

                    $officeOathModel = EmployeeOfficeOath::findOne(['work_experience_id' => $idModel->id]) ? EmployeeOfficeOath::findOne(['work_experience_id' => $idModel->id]) : new EmployeeOfficeOath();
                    $officeOathModel->work_experience_id = $idModel->id;
                    $officeOathModel->save();

                    $nosaNosiModel = !$idModel->isNewRecord ? EmployeeNosaNosi::findOne(['work_experience_id' => $idModel->id]) ? EmployeeNosaNosi::findOne(['work_experience_id' => $idModel->id]) : new EmployeeNosaNosi() : new EmployeeNosaNosi();
                    $nosaNosiModel->work_experience_id = $idModel->id;
                    $nosaNosiModel->save();
                }


            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        return $this->renderAjax('work-experience', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateAppointment($emp_id, $agency, $position, $appointment, $grade, $monthly_salary, $date_start, $step, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $workExperienceModel = EmployeeWorkExperience::findOne([
            'emp_id' => $emp_id,
            'agency' => $agency,
            'position' => $position,
            'appointment' => $appointment,
            'grade' => $grade,
            'monthly_salary' => $monthly_salary,
            'date_start' => $date_start,
            'step' => $step,
        ]);

        $appointmentModel = EmployeeAppointment::findOne(['work_experience_id' => $workExperienceModel->id->id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            Yii::$app->request->post()
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($appointmentModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Work experience has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving work experience');
            }
        }

        return $this->renderAjax('_appointment-form', [
            'model' => $model,
            'appointmentModel' => $appointmentModel,
            'idx' => $idx
        ]);
    }

    public function actionUpdateDutyAssumption($emp_id, $agency, $position, $appointment, $grade, $monthly_salary, $date_start, $step, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $workExperienceModel = EmployeeWorkExperience::findOne([
            'emp_id' => $emp_id,
            'agency' => $agency,
            'position' => $position,
            'appointment' => $appointment,
            'grade' => $grade,
            'monthly_salary' => $monthly_salary,
            'date_start' => $date_start,
            'step' => $step,
        ]);

        $dutyAssumptionModel = EmployeeDutyAssumption::findOne(['work_experience_id' => $workExperienceModel->id->id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            Yii::$app->request->post()
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($dutyAssumptionModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Work experience has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving work experience');
            }
        }

        return $this->renderAjax('_duty-assumption-form', [
            'model' => $model,
            'dutyAssumptionModel' => $dutyAssumptionModel,
            'idx' => $idx
        ]);
    }

    public function actionUpdatePositionDescription($emp_id, $agency, $position, $appointment, $grade, $monthly_salary, $date_start, $step, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $workExperienceModel = EmployeeWorkExperience::findOne([
            'emp_id' => $emp_id,
            'agency' => $agency,
            'position' => $position,
            'appointment' => $appointment,
            'grade' => $grade,
            'monthly_salary' => $monthly_salary,
            'date_start' => $date_start,
            'step' => $step,
        ]);

        $positionDescriptionModel = EmployeePositionDescription::findOne(['work_experience_id' => $workExperienceModel->id->id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            Yii::$app->request->post()
        ){
            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($positionDescriptionModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Work experience has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving work experience');
            }
        }

        return $this->renderAjax('_position-description-form', [
            'model' => $model,
            'positionDescriptionModel' => $positionDescriptionModel,
            'idx' => $idx
        ]);
    }

    public function actionUpdateOfficeOath($emp_id, $agency, $position, $appointment, $grade, $monthly_salary, $date_start, $step, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $workExperienceModel = EmployeeWorkExperience::findOne([
            'emp_id' => $emp_id,
            'agency' => $agency,
            'position' => $position,
            'appointment' => $appointment,
            'grade' => $grade,
            'monthly_salary' => $monthly_salary,
            'date_start' => $date_start,
            'step' => $step,
        ]);

        $officeOathModel = EmployeeOfficeOath::findOne(['work_experience_id' => $workExperienceModel->id->id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            Yii::$app->request->post()
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($officeOathModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Work experience has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving work experience');
            }
        }

        return $this->renderAjax('_office-oath-form', [
            'model' => $model,
            'officeOathModel' => $officeOathModel,
            'idx' => $idx
        ]);
    }

    public function actionUpdateNosaNosi($emp_id, $agency, $position, $appointment, $grade, $monthly_salary, $date_start, $step, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $workExperienceModel = EmployeeWorkExperience::findOne([
            'emp_id' => $emp_id,
            'agency' => $agency,
            'position' => $position,
            'appointment' => $appointment,
            'grade' => $grade,
            'monthly_salary' => $monthly_salary,
            'date_start' => $date_start,
            'step' => $step,
        ]);

        $nosaNosiModel = EmployeeNosaNosi::findOne(['work_experience_id' => $workExperienceModel->id->id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            Yii::$app->request->post()
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($nosaNosiModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Work experience has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving work experience');
            }
        }

        return $this->renderAjax('_nosa-nosi-form', [
            'model' => $model,
            'nosaNosiModel' => $nosaNosiModel,
            'idx' => $idx
        ]);
    }

    public function actionViewVoluntaryWork($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeVoluntaryWork::find()
                ->where(['emp_id' => $model->emp_id])
                ->orderBy([
                    'from_date' => SORT_DESC
                ]),
        ]);

        $voluntaryWorks = EmployeeVoluntaryWork::find()
            ->where(['emp_id' => $model->emp_id])
            ->orderBy([
                'from_date' => SORT_DESC
            ])
            ->all();
        
        $voluntaryWorkModels = [];

        if(!empty($voluntaryWorks))
        {
            foreach($voluntaryWorks as $idx => $voluntaryWork)
            {
                $voluntaryWorkModels[$idx + 1] = $voluntaryWork;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeVoluntaryWork');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedVoluntaryWorks = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedVoluntaryWorks[] = $voluntaryWorkModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedVoluntaryWorks)){
                    foreach($selectedVoluntaryWorks as $selectedVoluntaryWork){
                        $voluntaryWorkId = EmployeeVoluntaryWorkId::findOne([
                            'emp_id' => $selectedVoluntaryWork->emp_id,
                            'name_add_org' => $selectedVoluntaryWork->name_add_org,
                            'from_date' => $selectedVoluntaryWork->from_date,
                        ]);

                        if($voluntaryWorkId){
                            $voluntaryWorkId->delete();
                        }
                        $selectedVoluntaryWork->delete();
                    }
                }

                $transaction->commit();
                return [
                    'success' => 'Voluntary work has been deleted successfully',
                    'indexes' => $selectedIndexes
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting voluntary work'];
            }
        }

        return $this->renderAjax('voluntary-work', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'voluntaryWorkModels' => $voluntaryWorkModels,
        ]);
    }

    public function actionCreateVoluntaryWork($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $voluntaryWorkModel = new EmployeeVoluntaryWork();
        $voluntaryWorkModel->emp_id = $model->emp_id;
        $voluntaryWorkModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $voluntaryWorkModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeVoluntaryWorkId();
        $idModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $voluntaryWorkModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->name_add_org = $voluntaryWorkModel->name_add_org;
                $idModel->from_date = $voluntaryWorkModel->from_date;
                $idModel->save();
                if($voluntaryWorkModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Voluntary work has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving voluntary work');
            }
        }

        return $this->renderAjax('_voluntary-work-form', [
            'model' => $model,
            'voluntaryWorkModel' => $voluntaryWorkModel,
            'idModel' => $idModel,
            'idx' => 0,
        ]);
    }

    public function actionUpdateVoluntaryWork($emp_id, $name_add_org, $from_date, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $voluntaryWorkModel = EmployeeVoluntaryWork::findOne([
            'emp_id' => $emp_id,
            'name_add_org' => $name_add_org,
            'from_date' => $from_date,
        ]) ? EmployeeVoluntaryWork::findOne([
            'emp_id' => $emp_id,
            'name_add_org' => $name_add_org,
            'from_date' => $from_date,
        ]) : new EmployeeVoluntaryWork();

        $voluntaryWorkModel->emp_id = $model->emp_id;
        $voluntaryWorkModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $voluntaryWorkModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeVoluntaryWorkId::findOne([
            'emp_id' => $emp_id,
            'name_add_org' => $name_add_org,
            'from_date' => $from_date,
        ]) ? EmployeeVoluntaryWorkId::findOne([
            'emp_id' => $emp_id,
            'name_add_org' => $name_add_org,
            'from_date' => $from_date,
        ]) : new EmployeeVoluntaryWorkId();

        $idModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $voluntaryWorkModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->name_add_org = $voluntaryWorkModel->name_add_org;
                $idModel->from_date = $voluntaryWorkModel->from_date;
                $idModel->save();
                if($voluntaryWorkModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Voluntary work has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving voluntary work');
            }
        }

        return $this->renderAjax('_voluntary-work-form', [
            'model' => $model,
            'voluntaryWorkModel' => $voluntaryWorkModel,
            'idModel' => $idModel,
            'idx' => $idx
        ]);
    }

    public function actionApproveVoluntaryWork($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $voluntaryWorks = EmployeeVoluntaryWork::find()
            ->where(['emp_id' => $model->emp_id])
            ->orderBy([
                'from_date' => SORT_DESC
            ])
            ->all();
            
            $voluntaryWorkModels = [];

            if(!empty($voluntaryWorks))
            {
                foreach($voluntaryWorks as $idx => $voluntaryWork)
                {
                    $voluntaryWorkModels[$idx + 1] = $voluntaryWork;
                }
            }

            $postData = Yii::$app->request->post('EmployeeVoluntaryWork');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedVoluntaryWorks = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedVoluntaryWorks[] = $voluntaryWorkModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if(!empty($selectedVoluntaryWorks)){
                    foreach($selectedVoluntaryWorks as $selectedVoluntaryWork){
                        $selectedVoluntaryWork->approval = 'yes';
                        $selectedVoluntaryWork->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedVoluntaryWork->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'voluntary work',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in voluntary work have been approved by the HR Unit');
                        
                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Voluntary work has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving voluntary work');
            }
        }
    }

    public function actionViewTraining($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeTraining::find()
                ->where(['emp_id' => $model->emp_id])
                ->orderBy([
                    'from_date' => SORT_DESC
                ]),
        ]);

        $trainings = EmployeeTraining::find()
            ->where(['emp_id' => $model->emp_id])
            ->orderBy([
                'from_date' => SORT_DESC
            ])
            ->all();
        
        $trainingModels = [];

        if(!empty($trainings))
        {
            foreach($trainings as $idx => $training)
            {
                $trainingModels[$idx + 1] = $training;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeTraining');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedTrainings = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedTrainings[] = $trainingModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedTrainings)){
                    foreach($selectedTrainings as $selectedTraining){
                        $trainingId = EmployeeTrainingId::findOne([
                            'emp_id' => $selectedTraining->emp_id,
                            'seminar_title' => $selectedTraining->seminar_title,
                            'from_date' => $selectedTraining->from_date,
                        ]);

                        if($trainingId){
                            $trainingId->delete();
                        }
                        $selectedTraining->delete();
                    }
                }

                $transaction->commit();
                return [
                    'success' => 'Training has been deleted successfully',
                    'indexes' => $selectedIndexes
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting training'];
            }
        }

        return $this->renderAjax('training', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'trainingModels' => $trainingModels,
        ]);
    }

    public function actionCreateTraining($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $trainingModel = new EmployeeTraining();
        $trainingModel->emp_id = $model->emp_id;
        $trainingModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $trainingModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeTrainingId();
        $idModel->emp_id = $model->emp_id;

        $disciplines = TrainingDiscipline::find()->all();
        $disciplines = ArrayHelper::map($disciplines, 'discipline', 'discipline');

        $categories = TrainingCategory::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $trainingModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->seminar_title = $trainingModel->seminar_title;
                $idModel->from_date = $trainingModel->from_date;
                $idModel->save();
                if($trainingModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Training has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving training');
            }
        }

        return $this->renderAjax('_training-form', [
            'model' => $model,
            'trainingModel' => $trainingModel,
            'idModel' => $idModel,
            'disciplines' => $disciplines,
            'categories' => $categories,
            'idx' => 0,
        ]);
    }

    public function actionUpdateTraining($emp_id, $seminar_title, $from_date, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $trainingModel = EmployeeTraining::findOne([
            'emp_id' => $emp_id,
            'seminar_title' => $seminar_title,
            'from_date' => $from_date,
        ]) ? EmployeeTraining::findOne([
            'emp_id' => $emp_id,
            'seminar_title' => $seminar_title,
            'from_date' => $from_date,
        ]) : new EmployeeTraining();

        $trainingModel->emp_id = $model->emp_id;
        $trainingModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $trainingModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeTrainingId::findOne([
            'emp_id' => $emp_id,
            'seminar_title' => $seminar_title,
            'from_date' => $from_date,
        ]) ? EmployeeTrainingId::findOne([
            'emp_id' => $emp_id,
            'seminar_title' => $seminar_title,
            'from_date' => $from_date,
        ]) : new EmployeeTrainingId();

        $idModel->emp_id = $model->emp_id;

        $disciplines = TrainingDiscipline::find()->all();
        $disciplines = ArrayHelper::map($disciplines, 'discipline', 'discipline');

        $categories = TrainingCategory::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $trainingModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->seminar_title = $trainingModel->seminar_title;
                $idModel->from_date = $trainingModel->from_date;
                $idModel->save();
                if($trainingModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Training has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving training');
            }
        }

        return $this->renderAjax('_training-form', [
            'model' => $model,
            'trainingModel' => $trainingModel,
            'idModel' => $idModel,
            'disciplines' => $disciplines,
            'categories' => $categories,
            'idx' => $idx
        ]);
    }

    public function actionApproveTraining($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $trainings = EmployeeTraining::find()
                ->where(['emp_id' => $model->emp_id])
                ->orderBy([
                    'from_date' => SORT_DESC
                ])
                ->all();
            
            $trainingModels = [];

            if(!empty($trainings))
            {
                foreach($trainings as $idx => $training)
                {
                    $trainingModels[$idx + 1] = $training;
                }
            }

            $postData = Yii::$app->request->post('EmployeeTraining');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedTrainings = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedTrainings[] = $trainingModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if(!empty($selectedTrainings)){
                    foreach($selectedTrainings as $selectedTraining){
                        $selectedTraining->approval = 'yes';
                        $selectedTraining->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedTraining->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'learning and development interventions / trainings attended',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in learning and development interventions / trainings attended have been approved by the HR Unit');
                        
                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Training has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving training');
            }
        }
    }

    public function actionViewOtherInformation($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        return $this->renderAjax('other-information', [
            'model' => $model
        ]);
    }

    public function actionViewSkill($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeOtherInfo::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    'type' => 'hobbies',
                    ])
                ->orderBy([
                    'description' => SORT_ASC
                ]),
        ]);

        $skills = EmployeeOtherInfo::find()
            ->where([
                'emp_id' => $model->emp_id,
                'type' => 'hobbies',
                ])
            ->orderBy([
                'description' => SORT_ASC
            ])
            ->all();
        
        $skillModels = [];

        if(!empty($skills))
        {
            foreach($skills as $idx => $skill)
            {
                $skillModels[$idx + 1] = $skill;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeOtherInfo');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedSkills = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedSkills[] = $skillModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedSkills)){
                    foreach($selectedSkills as $selectedSkill){
                        $skillId = EmployeeOtherInfoId::findOne([
                            'emp_id' => $selectedSkill->emp_id,
                            'type' => $selectedSkill->type,
                            'description' => $selectedSkill->description,
                        ]);

                        if($skillId){
                            $skillId->delete();
                        }
                        $selectedSkill->delete();
                    }
                }

                $transaction->commit();
                return [
                    'success' => 'Special skill and hobby has been deleted successfully',
                    'indexes' => $selectedIndexes
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting special skill and hobby'];
            }
        }

        return $this->renderAjax('skill', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'skillModels' => $skillModels,
        ]);
    }

    public function actionCreateSkill($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $skillModel = new EmployeeOtherInfo();
        $skillModel->scenario = 'staffSkill';
        $skillModel->emp_id = $model->emp_id;
        $skillModel->type = 'hobbies';
        $skillModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $skillModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeOtherInfoId();
        $idModel->emp_id = $model->emp_id;
        $idModel->type = 'hobbies';

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $skillModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->type = $skillModel->type;
                $idModel->description = $skillModel->description;
                $idModel->save();
                if($skillModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Special skill/hobby has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving special skill/hobby');
            }
        }

        return $this->renderAjax('_skill-form', [
            'model' => $model,
            'skillModel' => $skillModel,
            'idModel' => $idModel,
            'idx' => 0,
        ]);
    }

    public function actionUpdateSkill($emp_id, $type, $description, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $skillModel = EmployeeOtherInfo::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) ? EmployeeOtherInfo::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) : new EmployeeOtherInfo();

        $skillModel->scenario = 'staffSkill';
        $skillModel->emp_id = $model->emp_id;
        $skillModel->type = 'hobbies';
        $skillModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $skillModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeOtherInfoId::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) ? EmployeeOtherInfoId::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) : new EmployeeOtherInfoId();

        $idModel->emp_id = $model->emp_id;
        $idModel->type = 'hobbies';

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $skillModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->type = $skillModel->type;
                $idModel->description = $skillModel->description;
                $idModel->save();
                if($skillModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Special skill/hobby has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving special skill/hobby');
            }
        }

        return $this->renderAjax('_skill-form', [
            'model' => $model,
            'skillModel' => $skillModel,
            'idModel' => $idModel,
            'idx' => $idx
        ]);
    }

    public function actionApproveSkill($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $skills = EmployeeOtherInfo::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    'type' => 'hobbies',
                    ])
                ->orderBy([
                    'description' => SORT_ASC
                ])
                ->all();
            
            $skillModels = [];

            if(!empty($skills))
            {
                foreach($skills as $idx => $skill)
                {
                    $skillModels[$idx + 1] = $skill;
                }
            }

            $postData = Yii::$app->request->post('EmployeeOtherInfo');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedSkills = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedSkills[] = $skillModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if(!empty($selectedSkills)){
                    foreach($selectedSkills as $selectedSkill){
                        $selectedSkill->approval = 'yes';
                        $selectedSkill->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedSkill->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'special skills and hobbies',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in special skills and hobbies have been approved by the HR Unit');
                        
                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Special skill and hobby has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving special skill and hobby');
            }
        }
    }

    public function actionViewRecognition($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeOtherInfo::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    'type' => 'recognition',
                    ])
                ->orderBy([
                    'year' => SORT_DESC,
                    'description' => SORT_ASC,
                ]),
        ]);

        $recognitions = EmployeeOtherInfo::find()
            ->where([
                'emp_id' => $model->emp_id,
                'type' => 'recognition',
                ])
            ->orderBy([
                'year' => SORT_DESC,
                'description' => SORT_ASC,
            ])
            ->all();
        
        $recognitionModels = [];

        if(!empty($recognitions))
        {
            foreach($recognitions as $idx => $recognition)
            {
                $recognitionModels[$idx + 1] = $recognition;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeOtherInfo');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedRecognitions = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedRecognitions[] = $recognitionModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedRecognitions)){
                    foreach($selectedRecognitions as $selectedRecognition){
                        $recognitionId = EmployeeOtherInfoId::findOne([
                            'emp_id' => $selectedRecognition->emp_id,
                            'type' => $selectedRecognition->type,
                            'description' => $selectedRecognition->description,
                        ]);

                        if($recognitionId){
                            $recognitionId->delete();
                        }
                        $selectedRecognition->delete();
                    }
                }

                $transaction->commit();
                return [
                    'success' => 'Non-academic distinction/recognition has been deleted successfully',
                    'indexes' => $selectedIndexes
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting non-academic distinction/recognition'];
            }
        }

        return $this->renderAjax('recognition', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'recognitionModels' => $recognitionModels,
        ]);
    }

    public function actionCreateRecognition($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $recognitionModel = new EmployeeOtherInfo();
        $recognitionModel->scenario = 'staffRecognition';
        $recognitionModel->emp_id = $model->emp_id;
        $recognitionModel->type = 'recognition';
        $recognitionModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $recognitionModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeOtherInfoId();
        $idModel->emp_id = $model->emp_id;
        $idModel->type = 'recognition';

        $scopes = [];

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $recognitionModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->type = $recognitionModel->type;
                $idModel->description = $recognitionModel->description;
                $idModel->save();
                if($recognitionModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Non-academic distinction/recognition has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving non-academic distinction/recognition');
            }
        }

        return $this->renderAjax('_recognition-form', [
            'model' => $model,
            'recognitionModel' => $recognitionModel,
            'idModel' => $idModel,
            'scopes' => $scopes,
            'idx' => 0,
        ]);
    }

    public function actionUpdateRecognition($emp_id, $type, $description, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $recognitionModel = EmployeeOtherInfo::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) ? EmployeeOtherInfo::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) : new EmployeeOtherInfo();

        $recognitionModel->scenario = 'staffRecognition';
        $recognitionModel->emp_id = $model->emp_id;
        $recognitionModel->type = 'recognition';
        $recognitionModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $recognitionModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeOtherInfoId::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) ? EmployeeOtherInfoId::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) : new EmployeeOtherInfoId();

        $idModel->emp_id = $model->emp_id;
        $idModel->type = 'recognition';

        $scopes = [];

        if($recognitionModel->internal_external == 'Internal'){
            $scopes = [
                        'Monthly' => 'Monthly',
                        'Quarterly' => 'Quarterly',
                        'Semestral'=> 'Semestral',
                        'Annual' => 'Annual'
                    ];
        }else if($recognitionModel->internal_external == 'External'){
            $scopes = [
                        'International' => 'International',
                        'National' => 'National',
                        'Regional' => 'Regional',
                        'Local' => 'Local'
                    ];
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $recognitionModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->type = $recognitionModel->type;
                $idModel->description = $recognitionModel->description;
                $idModel->save();
                if($recognitionModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Non-academic distinction/recognition has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving non-academic distinction/recognition');
            }
        }

        return $this->renderAjax('_recognition-form', [
            'model' => $model,
            'recognitionModel' => $recognitionModel,
            'idModel' => $idModel,
            'scopes' => $scopes,
            'idx' => $idx
        ]);
    }

    public function actionApproveRecognition($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $recognitions = EmployeeOtherInfo::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    'type' => 'recognition',
                    ])
                ->orderBy([
                    'year' => SORT_DESC,
                    'description' => SORT_ASC,
                ])
                ->all();
            
            $recognitionModels = [];

            if(!empty($recognitions))
            {
                foreach($recognitions as $idx => $recognition)
                {
                    $recognitionModels[$idx + 1] = $recognition;
                }
            }

            $postData = Yii::$app->request->post('EmployeeOtherInfo');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedRecognitions = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedRecognitions[] = $recognitionModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedRecognitions)){
                    foreach($selectedRecognitions as $selectedRecognition){
                        $selectedRecognition->approval = 'yes';
                        $selectedRecognition->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedRecognition->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'non-academic distinctions/recognitions',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in non-academic distinctions/recognitions have been approved by the HR Unit');
                        
                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Non-academic distinction/recognition has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving non-academic distinction/recognition');
            }
        }
    }

    public function actionScopeList($type)
    {
        $arr = [];
        $arr[] = ['id' => '', 'text' => ''];
        if($type == 'Internal'){
            $arr[] = ['id' => 'Monthly', 'text' => 'Monthly'];
            $arr[] = ['id' => 'Quarterly', 'text' => 'Quarterly'];
            $arr[] = ['id' => 'Semestral', 'text' => 'Semestral'];
            $arr[] = ['id' => 'Annual', 'text' => 'Annual'];
        }else if($type == 'External'){
            $arr[] = ['id' => 'International', 'text' => 'International'];
            $arr[] = ['id' => 'National', 'text' => 'National'];
            $arr[] = ['id' => 'Regional', 'text' => 'Regional'];
            $arr[] = ['id' => 'Local', 'text' => 'Local'];
        }

        \Yii::$app->response->format = 'json';
        return $arr;
    }

    public function actionViewOrganization($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeOtherInfo::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    'type' => 'membership',
                    ])
                ->orderBy([
                    'description' => SORT_ASC
                ]),
        ]);

        $organizations = EmployeeOtherInfo::find()
            ->where([
                'emp_id' => $model->emp_id,
                'type' => 'membership',
                ])
            ->orderBy([
                'description' => SORT_ASC
            ])
            ->all();
        
        $organizationModels = [];

        if(!empty($organizations))
        {
            foreach($organizations as $idx => $organization)
            {
                $organizationModels[$idx + 1] = $organization;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeOtherInfo');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedOrganizations = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedOrganizations[] = $organizationModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedOrganizations)){
                    foreach($selectedOrganizations as $selectedOrganization){
                        $organizationId = EmployeeOtherInfoId::findOne([
                            'emp_id' => $selectedOrganization->emp_id,
                            'type' => $selectedOrganization->type,
                            'description' => $selectedOrganization->description,
                        ]);

                        if($organizationId){
                            $organizationId->delete();
                        }
                        $selectedOrganization->delete();
                    }
                }

                $transaction->commit();
                return [
                    'success' => 'Membership/organization has been deleted successfully',
                    'indexes' => $selectedIndexes
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting membership/organization'];
            }
        }

        return $this->renderAjax('organization', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'organizationModels' => $organizationModels,
        ]);
    }

    public function actionCreateOrganization($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $organizationModel = new EmployeeOtherInfo();
        $organizationModel->scenario = 'staffOrganization';
        $organizationModel->emp_id = $model->emp_id;
        $organizationModel->type = 'membership';
        $organizationModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $organizationModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = new EmployeeOtherInfoId();
        $idModel->emp_id = $model->emp_id;
        $idModel->type = 'membership';

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $organizationModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->type = $organizationModel->type;
                $idModel->description = $organizationModel->description;
                $idModel->save();
                if($organizationModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Membership in association/organization has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving membership in association/organization');
            }
        }

        return $this->renderAjax('_organization-form', [
            'model' => $model,
            'organizationModel' => $organizationModel,
            'idModel' => $idModel,
            'idx' => 0,
        ]);
    }

    public function actionUpdateOrganization($emp_id, $type, $description, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $organizationModel = EmployeeOtherInfo::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) ? EmployeeOtherInfo::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) : new EmployeeOtherInfo();

        $organizationModel->scenario = 'staffOrganization';
        $organizationModel->emp_id = $model->emp_id;
        $organizationModel->type = 'membership';
        $organizationModel->approval = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? 'yes' : 'no';
        $organizationModel->approver = Yii::$app->user->can('HR') && (Yii::$app->user->identity->userinfo->EMP_N != $model->emp_id) ? Yii::$app->user->identity->userinfo->FIRST_M : '';

        $idModel = EmployeeOtherInfoId::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) ? EmployeeOtherInfoId::findOne([
            'emp_id' => $emp_id,
            'type' => $type,
            'description' => $description,
        ]) : new EmployeeOtherInfoId();

        $idModel->emp_id = $model->emp_id;
        $idModel->type = 'membership';

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $organizationModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                $idModel->type = $organizationModel->type;
                $idModel->description = $organizationModel->description;
                $idModel->save();
                if($organizationModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Membership in association/organization has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving membership in association/organization');
            }
        }

        return $this->renderAjax('_organization-form', [
            'model' => $model,
            'organizationModel' => $organizationModel,
            'idModel' => $idModel,
            'idx' => $idx
        ]);
    }

    public function actionApproveOrganization($id)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);

            $organizations = EmployeeOtherInfo::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    'type' => 'membership',
                    ])
                ->orderBy([
                    'description' => SORT_ASC
                ])
                ->all();
            
            $organizationModels = [];

            if(!empty($organizations))
            {
                foreach($organizations as $idx => $organization)
                {
                    $organizationModels[$idx + 1] = $organization;
                }
            }

            $postData = Yii::$app->request->post('EmployeeOtherInfo');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedOrganizations = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedOrganizations[] = $organizationModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if(!empty($selectedOrganizations)){
                    foreach($selectedOrganizations as $selectedOrganization){
                        $selectedOrganization->approval = 'yes';
                        $selectedOrganization->approver = Yii::$app->user->identity->userinfo->FIRST_M;
                        $selectedOrganization->save(false);
                    }

                    $mailer = Yii::$app->mailer;
                    $staff = User::find()
                        ->leftJoin('user_info', 'user_info.user_id = user.id')
                        ->andWhere(['user_info.EMP_N' => $model->emp_id])
                        ->one();

                    if($staff){
                        $message = $mailer->compose('approve-pds-html', [
                            'model' => $model,
                            'title' => 'membership in associations/organization',
                        ])
                        ->setFrom('nro1.mailer@neda.gov.ph')
                        ->setTo($staff->email)
                        ->setSubject('IPMS Notification: Your entries in membership in associations/organization have been approved by the HR Unit');
                        
                        $message->send();
                    }

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Membership in associations/organization has been approved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while approving membership/organization');
            }
        }
    }

    public function actionViewQuestion($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $questionsModels = [];

        $q1A = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 36,
            'list' => 'A',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 36,
            'list' => 'A',
        ]) : new EmployeeQuestion();

        $q1A->emp_id = $model->emp_id;
        $q1A->number = 36;
        $q1A->list = 'A';

        $questionModels['q1A'] = $q1A;

        $q1B = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 36,
            'list' => 'B',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 36,
            'list' => 'B',
        ]) : new EmployeeQuestion();

        $q1B->emp_id = $model->emp_id;
        $q1B->number = 36;
        $q1B->list = 'B';

        $questionModels['q1B'] = $q1B;

        $q2A = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 37,
            'list' => 'B',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 37,
            'list' => 'B',
        ]) : new EmployeeQuestion();

        $q2A->emp_id = $model->emp_id;
        $q2A->number = 37;
        $q2A->list = 'B';

        $questionModels['q2A'] = $q2A;

        $q2B = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 37,
            'list' => 'A',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 37,
            'list' => 'A',
        ]) : new EmployeeQuestion();

        $q2B->emp_id = $model->emp_id;
        $q2B->number = 37;
        $q2B->list = 'A';

        $questionModels['q2B'] = $q2B;

        $q3 = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 38,
            'list' => 'NA',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 38,
            'list' => 'NA',
        ]) : new EmployeeQuestion();

        $q3->emp_id = $model->emp_id;
        $q3->number = 38;
        $q3->list = 'NA';

        $questionModels['q3'] = $q3;

        $q4 = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 39,
            'list' => 'NA',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 39,
            'list' => 'NA',
        ]) : new EmployeeQuestion();

        $q4->emp_id = $model->emp_id;
        $q4->number = 39;
        $q4->list = 'NA';

        $questionModels['q4'] = $q4;
        
        $q5A = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 40,
            'list' => 'NA',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 40,
            'list' => 'NA',
        ]) : new EmployeeQuestion();

        $q5A->emp_id = $model->emp_id;
        $q5A->number = 40;
        $q5A->list = 'NA';

        $questionModels['q5A'] = $q5A;

        $q5B = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 40,
            'list' => 'B',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 40,
            'list' => 'B',
        ]) : new EmployeeQuestion();

        $q5B->emp_id = $model->emp_id;
        $q5B->number = 40;
        $q5B->list = 'B';

        $questionModels['q5B'] = $q5B;

        $q6 = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 42,
            'list' => 'NA',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 42,
            'list' => 'NA',
        ]) : new EmployeeQuestion();

        $q6->emp_id = $model->emp_id;
        $q6->number = 42;
        $q6->list = 'NA';

        $questionModels['q6'] = $q6;

        $q7A = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 41,
            'list' => 'A',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 41,
            'list' => 'A',
        ]) : new EmployeeQuestion();

        $q7A->emp_id = $model->emp_id;
        $q7A->number = 41;
        $q7A->list = 'A';

        $questionModels['q7A'] = $q7A;

        $q7B = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 41,
            'list' => 'B',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 41,
            'list' => 'B',
        ]) : new EmployeeQuestion();

        $q7B->emp_id = $model->emp_id;
        $q7B->number = 41;
        $q7B->list = 'B';

        $questionModels['q7B'] = $q7B;

        $q7C = EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 41,
            'list' => 'C',
        ]) ? EmployeeQuestion::findOne([
            'emp_id' => $model->emp_id,
            'number' => 41,
            'list' => 'C',
        ]) : new EmployeeQuestion();

        $q7C->emp_id = $model->emp_id;
        $q7C->number = 41;
        $q7C->list = 'C';

        $questionModels['q7C'] = $q7C;

        $questions = [];

        $questions['q1']['question'] = 'Are you related by consanguinity or affinity to the appointing or recommending authority, or to the chief of bureau or office or to the person who has immediate supervision over you in the Office, Bureau or Department where you will be apppointed,';
        $questions['q1']['A'] = 'within the third degree?';
        $questions['q1']['B'] = 'within the fourth degree (for Local Government Unit - Career Employees)?';
        $questions['q2']['A'] = 'Have you ever been found guilty of any administrative offense?';
        $questions['q2']['B'] = 'Have you been criminally charged before any court?';
        $questions['q3']['question'] = 'Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?';
        $questions['q4']['question'] = 'Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?';
        $questions['q5']['A'] = 'Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?';
        $questions['q5']['B'] = 'Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?';
        $questions['q6']['question'] = 'Have you acquired the status of an immigrant or permanent resident of another country?';
        $questions['q7']['question'] = "Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:";
        $questions['q7']['A'] = 'Are you a member of any indigenous group?';
        $questions['q7']['B'] = 'Are you a person with disability?';
        $questions['q7']['C'] = 'Are you a solo parent?';

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post('EmployeeQuestion');

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {

                foreach($questionModels as $key => $questionModel){
                    $questionModel->answer = isset($postData[$key]['answer']) ? $postData[$key]['answer'] : 'false'; 
                    $questionModel->yes_details = isset($postData[$key]['yes_details']) ? $postData[$key]['yes_details'] : '';
                    $questionModel->save(false); 
                }

                $transaction->commit();
                return ['success' => 'Questions have been saved successfully'];

            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while saving questions'];
            }
        }

        return $this->renderAjax('questions', [
            'model' => $model,
            'questions' => $questions,
            'questionModels' => $questionModels,
        ]);
    }

    public function actionViewReference($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => EmployeeReference::find()
                ->where([
                    'emp_id' => $model->emp_id,
                    ])
                ->orderBy([
                    'ref_name' => SORT_ASC
                ]),
        ]);

        $references = EmployeeReference::find()
            ->where([
                'emp_id' => $model->emp_id,
                ])
            ->orderBy([
                'ref_name' => SORT_ASC
            ])
            ->all();
        
        $referenceModels = [];

        if(!empty($references))
        {
            foreach($references as $idx => $reference)
            {
                $referenceModels[$idx + 1] = $reference;
            }
        }

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeReference');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedReferences = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedReferences[] = $referenceModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedReferences)){
                    foreach($selectedReferences as $selectedReference){
                        $selectedReference->delete();
                    }
                }

                $transaction->commit();
                return [
                    'success' => 'Reference has been deleted successfully',
                    'indexes' => $selectedIndexes
                ];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while deleting reference'];
            }
        }

        return $this->renderAjax('reference', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'referenceModels' => $referenceModels,
        ]);
    }

    public function actionCreateReference($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        $referenceModel = new EmployeeReference();
        $referenceModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $referenceModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($referenceModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Reference has been added successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving reference');
            }
        }

        return $this->renderAjax('_reference-form', [
            'model' => $model,
            'referenceModel' => $referenceModel,
            'idx' => 0,
        ]);
    }

    public function actionUpdateReference($emp_id, $ref_name, $idx)
    {
        $model = Employee::findOne(['emp_id' => $emp_id]);

        $referenceModel = EmployeeReference::findOne([
            'emp_id' => $emp_id,
            'ref_name' => $ref_name,
        ]) ? EmployeeReference::findOne([
            'emp_id' => $emp_id,
            'ref_name' => $ref_name,
        ]) : new EmployeeReference();

        $referenceModel->emp_id = $model->emp_id;

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if(
            $referenceModel->load(Yii::$app->request->post()) /* && 
            $idModel->load(Yii::$app->request->post()) */
        ){
            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if($referenceModel->save()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Reference has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving reference');
            }
        }

        return $this->renderAjax('_reference-form', [
            'model' => $model,
            'referenceModel' => $referenceModel,
            'idx' => $idx
        ]);
    }

    public function actionExcel($id)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        if(!Yii::$app->user->can('HR')){
            if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }

        $templatePath = Yii::getAlias('@frontend') . '/web/templates/pds.xlsx';

        $spreadsheet = IOFactory::load($templatePath);

        $firstSheet = $spreadsheet->getSheetByName('C1');

        $firstSheet->setCellValue('D10', strtoupper($model->lname));     
        $firstSheet->setCellValue('D11', strtoupper($model->fname));     
        $firstSheet->setCellValue('D12', strtoupper($model->mname));     
        $firstSheet->setCellValue('D13', date("m/d/Y", strtotime($model->birth_date)));
        $firstSheet->setCellValue('D15', $model->birth_place);

        if($model->citizenship == 'Filipino')
        {
            $firstSheet->setCellValue('J13', '☑️ Filipino    ☐ Dual Citizenship'); 
        }else{
            $firstSheet->setCellValue('J13', '☐ Filipino    ☐ Dual Citizenship'); 
        }
        
        $firstSheet->setCellValue('L14', '☐ by birth   ☐ by naturalization'); 
        
        if($model->gender == 'Male')
        {
            $firstSheet->setCellValue('D16', '☑️ Male    ☐ Female'); 
        }else{
            $firstSheet->setCellValue('D16', '☐ Male    ☑️ Female'); 
        }

        if($model->civil_status == 'Single')
        {
            $firstSheet->setCellValue('D17', '☑️ Single   ☐ Married'); 
            $firstSheet->setCellValue('D18', '☐ Widowed   ☐ Separated'); 
            $firstSheet->setCellValue('D20', '☐ Other/s:'); 
        }else if($model->civil_status == 'Married'){
            $firstSheet->setCellValue('D17', '☐ Single   ☑️ Married'); 
            $firstSheet->setCellValue('D18', '☐ Widowed   ☐ Separated'); 
            $firstSheet->setCellValue('D20', '☐ Other/s:'); 
        }else if($model->civil_status == 'Widowed'){
            $firstSheet->setCellValue('D17', '☐ Single   ☐ Married'); 
            $firstSheet->setCellValue('D18', '☑️ Widowed   ☐ Separated'); 
            $firstSheet->setCellValue('D20', '☐ Other/s:'); 
        }else if($model->civil_status == 'Separated'){
            $firstSheet->setCellValue('D17', '☐ Single   ☐ Married'); 
            $firstSheet->setCellValue('D18', '☐ Widowed   ☑️ Separated'); 
            $firstSheet->setCellValue('D20', '☐ Other/s:'); 
        }else{
            $firstSheet->setCellValue('D17', '☐ Single   ☐ Married'); 
            $firstSheet->setCellValue('D18', '☐ Widowed   ☐ Separated'); 
            $firstSheet->setCellValue('D20', '☑️ Other/s: '.$model->civil_status); 
        }

        $residentialAddress = $model->getAddresses()->where(['type' => 'residential'])->one();
        $permanentAddress = $model->getAddresses()->where(['type' => 'permanent'])->one();

        $firstSheet->setCellValue('I17', ($residentialAddress ? $residentialAddress->house_no : ''));
        $firstSheet->setCellValue('L17', ($residentialAddress ? $residentialAddress->street : ''));
        $firstSheet->setCellValue('I19', ($residentialAddress ? $residentialAddress->subdivision : ''));
        $firstSheet->setCellValue('L19', ($residentialAddress ? $residentialAddress->barangay : ''));
        $firstSheet->setCellValue('I22', ($residentialAddress ? $residentialAddress->city : ''));
        $firstSheet->setCellValue('L22', ($residentialAddress ? $residentialAddress->province : ''));
        $firstSheet->setCellValue('I24', $model->residential_zip_code);

        $firstSheet->setCellValue('I25', ($permanentAddress ? $permanentAddress->house_no : ''));
        $firstSheet->setCellValue('L25', ($permanentAddress ? $permanentAddress->street : ''));
        $firstSheet->setCellValue('I27', ($permanentAddress ? $permanentAddress->subdivision : ''));
        $firstSheet->setCellValue('L27', ($permanentAddress ? $permanentAddress->barangay : ''));
        $firstSheet->setCellValue('I29', ($permanentAddress ? $permanentAddress->city : ''));
        $firstSheet->setCellValue('L29', ($permanentAddress ? $permanentAddress->province : ''));
        $firstSheet->setCellValue('I31', $model->permanent_zip_code);

        $firstSheet->setCellValue('D22', $model->height);
        $firstSheet->setCellValue('D24', $model->weight);
        $firstSheet->setCellValue('D25', $model->blood_type);
        $firstSheet->setCellValueExplicit('D27', (string) $model->GSIS, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $firstSheet->setCellValueExplicit('D29', (string) $model->Pag_ibig, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $firstSheet->setCellValueExplicit('D31', (string) $model->Philhealth, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $firstSheet->setCellValueExplicit('D32', (string) $model->SSS, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $firstSheet->setCellValueExplicit('D33', (string) $model->TIN, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $firstSheet->setCellValue('D34', $model->emp_id);

        $firstSheet->setCellValue('I32', $model->permanent_tel_no);
        $firstSheet->setCellValue('I33', $model->cell_no);
        $firstSheet->setCellValue('I34', $model->e_mail_add);

        $firstSheet->setCellValue('D36', $model->spouse_surname);
        $firstSheet->setCellValue('D37', $model->spouse_firstname);
        $firstSheet->setCellValue('D38', $model->spouse_middlename);
        $firstSheet->setCellValue('D39', ($model->spouseOccupation ? $model->spouseOccupation->occupation : ''));
        $firstSheet->setCellValue('D40', ($model->spouseOccupation ? $model->spouseOccupation->employer_business_name : ''));
        $firstSheet->setCellValue('D41', ($model->spouseOccupation ? $model->spouseOccupation->business_address : ''));
        $firstSheet->setCellValue('D42', ($model->spouseOccupation ? $model->spouseOccupation->tel_no : ''));

        $firstSheet->setCellValue('D43', $model->father_surname);
        $firstSheet->setCellValue('D44', $model->father_firstname);
        $firstSheet->setCellValue('D45', $model->father_middlename);

        $firstSheet->setCellValue('D46', $model->mother_maiden_name);
        $firstSheet->setCellValue('D47', $model->mother_surname);
        $firstSheet->setCellValue('D48', $model->mother_firstname);
        $firstSheet->setCellValue('D49', $model->mother_middlename);

        $children = $model->getChildren()->orderBy(['birthday' => SORT_DESC])->limit(12)->asArray()->all();

        $childrenCell = 37;

        if(!empty($children)){
            foreach($children as $child){
                $firstSheet->setCellValue('I'.$childrenCell, $child['child_name']);
                $firstSheet->setCellValue('M'.$childrenCell, date("m/d/Y", strtotime($child['birthday'])));

                $childrenCell++;
            }
        }

        $elementaries = $model->getEducations()->where(['level' => 'Elementary', 'approval' => 'yes'])->orderBy(['from_date' => SORT_ASC])->asArray()->all();

        $elementaryCell = 54;
        $highSchoolCell = 55;

        if(count($elementaries) > 1){
            $j = 1;
            for($i = 0; $i < count($elementaries) - 1; $i++){
                $firstSheet->insertNewRowBefore($highSchoolCell);

                $aboveRowStyle = $firstSheet->getStyle('D' . $elementaryCell . ':N' . $elementaryCell);

                $borderStyle = [
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                    ],
                ];

                $firstSheet->getStyle('J' . ($elementaryCell + $j))->applyFromArray($borderStyle);
                $firstSheet->getStyle('L' . ($elementaryCell + $j))->applyFromArray($borderStyle);

                $j++;
            }
        }

        if(!empty($elementaries)){
            foreach($elementaries as $elementary){
                $firstSheet->setCellValue('D'.$elementaryCell, $elementary['school']);
                $firstSheet->setCellValue('G'.$elementaryCell, $elementary['course']);
                $firstSheet->setCellValue('J'.$elementaryCell, $elementary['from_date']);
                $firstSheet->setCellValue('K'.$elementaryCell, $elementary['to_date']);
                $firstSheet->setCellValue('L'.$elementaryCell, $elementary['highest_attainment']);
                $firstSheet->setCellValue('M'.$elementaryCell, $elementary['year_graduated']);
                $firstSheet->setCellValue('N'.$elementaryCell, $elementary['awards']);

                $firstSheet->mergeCells('D' . ($elementaryCell) . ':F' . ($elementaryCell));
                $firstSheet->mergeCells('G' . ($elementaryCell) . ':I' . ($elementaryCell));

                $elementaryCell++;
                $highSchoolCell++;
            }
        }else{
            $highSchoolCell++;
        }

        $highSchoolCell -= 1;

        $secondaries = $model->getEducations()->where(['level' => 'Secondary', 'approval' => 'yes'])->orderBy(['from_date' => SORT_ASC])->asArray()->all();

        $vocationalCell = $highSchoolCell + 1;

        if(count($secondaries) > 1){
            $j = 1;
            for($i = 0; $i < count($secondaries) - 1; $i++){
                $firstSheet->insertNewRowBefore($vocationalCell);

                $aboveRowStyle = $firstSheet->getStyle('D' . $highSchoolCell . ':N' . $highSchoolCell);

                $borderStyle = [
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                    ],
                ];

                $firstSheet->getStyle('J' . ($highSchoolCell + $j))->applyFromArray($borderStyle);
                $firstSheet->getStyle('L' . ($highSchoolCell + $j))->applyFromArray($borderStyle);

                $j++;
            }
        }

        if(!empty($secondaries)){
            foreach($secondaries as $secondary){
                $firstSheet->setCellValue('D'.$highSchoolCell, $secondary['school']);
                $firstSheet->setCellValue('G'.$highSchoolCell, $secondary['course']);
                $firstSheet->setCellValue('J'.$highSchoolCell, $secondary['from_date']);
                $firstSheet->setCellValue('K'.$highSchoolCell, $secondary['to_date']);
                $firstSheet->setCellValue('L'.$highSchoolCell, $secondary['highest_attainment']);
                $firstSheet->setCellValue('M'.$highSchoolCell, $secondary['year_graduated']);
                $firstSheet->setCellValue('N'.$highSchoolCell, $secondary['awards']);

                $firstSheet->mergeCells('D' . ($highSchoolCell) . ':F' . ($highSchoolCell));
                $firstSheet->mergeCells('G' . ($highSchoolCell) . ':I' . ($highSchoolCell));

                $highSchoolCell++;
                $vocationalCell++;
            }
        }else{
            $vocationalCell++;
        }

        $vocationalCell -= 1;

        $vocationals = $model->getEducations()->where(['level' => 'Vocational/Trade Course', 'approval' => 'yes'])->orderBy(['from_date' => SORT_ASC])->asArray()->all();

        $collegeCell = $vocationalCell + 1;

        if(count($vocationals) > 1){
            $j = 1;
            for($i = 0; $i < count($vocationals) - 1; $i++){
                $firstSheet->insertNewRowBefore($collegeCell);

                $aboveRowStyle = $firstSheet->getStyle('D' . $vocationalCell . ':N' . $vocationalCell);

                $borderStyle = [
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                    ],
                ];

                $firstSheet->getStyle('J' . ($vocationalCell + $j))->applyFromArray($borderStyle);
                $firstSheet->getStyle('L' . ($vocationalCell + $j))->applyFromArray($borderStyle);

                $j++;
            }
        }

        if(!empty($vocationals)){
            foreach($vocationals as $vocational){
                $firstSheet->setCellValue('D'.$vocationalCell, $vocational['school']);
                $firstSheet->setCellValue('G'.$vocationalCell, $vocational['course']);
                $firstSheet->setCellValue('J'.$vocationalCell, $vocational['from_date']);
                $firstSheet->setCellValue('K'.$vocationalCell, $vocational['to_date']);
                $firstSheet->setCellValue('L'.$vocationalCell, $vocational['highest_attainment']);
                $firstSheet->setCellValue('M'.$vocationalCell, $vocational['year_graduated']);
                $firstSheet->setCellValue('N'.$vocationalCell, $vocational['awards']);

                $firstSheet->mergeCells('D' . ($vocationalCell) . ':F' . ($vocationalCell));
                $firstSheet->mergeCells('G' . ($vocationalCell) . ':I' . ($vocationalCell));

                $vocationalCell++;
                $collegeCell++;
            }
        }else{
            $collegeCell++;
        }

        $collegeCell -= 1;

        $colleges = $model->getEducations()->where(['level' => 'College', 'approval' => 'yes'])->orderBy(['from_date' => SORT_ASC])->asArray()->all();

        $graduateCell = $collegeCell + 1;

        if(count($colleges) > 1){
            $j = 1;
            for($i = 0; $i < count($colleges) - 1; $i++){
                $firstSheet->insertNewRowBefore($graduateCell);

                $aboveRowStyle = $firstSheet->getStyle('D' . $collegeCell . ':N' . $collegeCell);

                $borderStyle = [
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                    ],
                ];

                $firstSheet->getStyle('J' . ($collegeCell + $j))->applyFromArray($borderStyle);
                $firstSheet->getStyle('L' . ($collegeCell + $j))->applyFromArray($borderStyle);

                $j++;
            }
        }

        if(!empty($colleges)){
            foreach($colleges as $college){
                $firstSheet->setCellValue('D'.$collegeCell, $college['school']);
                $firstSheet->setCellValue('G'.$collegeCell, $college['course']);
                $firstSheet->setCellValue('J'.$collegeCell, $college['from_date']);
                $firstSheet->setCellValue('K'.$collegeCell, $college['to_date']);
                $firstSheet->setCellValue('L'.$collegeCell, $college['highest_attainment']);
                $firstSheet->setCellValue('M'.$collegeCell, $college['year_graduated']);
                $firstSheet->setCellValue('N'.$collegeCell, $college['awards']);

                $firstSheet->mergeCells('D' . ($collegeCell) . ':F' . ($collegeCell));
                $firstSheet->mergeCells('G' . ($collegeCell) . ':I' . ($collegeCell));

                $collegeCell++;
                $graduateCell++;
            }
        }else{
            $graduateCell++;
        }

        $graduateCell -= 1;

        $graduates = $model->getEducations()->where(['level' => 'Graduate Studies', 'approval' => 'yes'])->orderBy(['from_date' => SORT_ASC])->asArray()->all();

        $educEndCell = $graduateCell + 1;

        if(count($graduates) > 1){
            $j = 0;
            for($i = 0; $i < count($graduates) - 1; $i++){
                if($i == 0){
                    $firstSheet->setCellValue('B'.$graduateCell + $j, 'GRADUATE STUDIES');
                }else{
                    $firstSheet->setCellValue('B'.$graduateCell + $j, '');
                }

                $firstSheet->insertNewRowBefore($educEndCell);

                $aboveRowStyle = $firstSheet->getStyle('D' . $graduateCell . ':N' . $graduateCell);

                $borderStyle = [
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                    ],
                ];

                $endBorderStyle = [
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN, // Adjust border style as needed
                            'color' => ['rgb' => '000000'], // Adjust border color as needed
                        ],
                    ],
                ];

                $firstSheet->getStyle('J' . ($graduateCell + $j))->applyFromArray($borderStyle);
                $firstSheet->getStyle('L' . ($graduateCell + $j))->applyFromArray($borderStyle);
                $firstSheet->getStyle('A' . ($graduateCell + $j))->applyFromArray($endBorderStyle);
                $firstSheet->getStyle('B' . ($graduateCell + $j))->applyFromArray($endBorderStyle);
                $firstSheet->getStyle('C' . ($graduateCell + $j))->applyFromArray($endBorderStyle);
                $firstSheet->getStyle('K' . ($graduateCell + $j))->applyFromArray($endBorderStyle);

                $j++;
            }
        }

        if(!empty($graduates)){
            foreach($graduates as $graduate){
                $firstSheet->setCellValue('D'.$graduateCell, $graduate['school']);
                $firstSheet->setCellValue('G'.$graduateCell, $graduate['course']);
                $firstSheet->setCellValue('J'.$graduateCell, $graduate['from_date']);
                $firstSheet->setCellValue('K'.$graduateCell, $graduate['to_date']);
                $firstSheet->setCellValue('L'.$graduateCell, $graduate['highest_attainment']);
                $firstSheet->setCellValue('M'.$graduateCell, $graduate['year_graduated']);
                $firstSheet->setCellValue('N'.$graduateCell, $graduate['awards']);

                $firstSheet->mergeCells('D' . ($graduateCell) . ':F' . ($graduateCell));
                $firstSheet->mergeCells('G' . ($graduateCell) . ':I' . ($graduateCell));

                $graduateCell++;
                $educEndCell++;
            }
        }else{
            $educEndCell++;
        }

        $secondSheet = $spreadsheet->getSheetByName('C2');

        $eligibilities = $model->getCivilServices()->where(['approval' => 'yes'])->orderBy(['exam_date' => SORT_DESC])->limit(7)->asArray()->all();
        
        $eligibilityCell = 5;

        if(!empty($eligibilities)){
            foreach($eligibilities as $eligibility){
                $secondSheet->setCellValue('A'.$eligibilityCell, $eligibility['eligibility']);
                $secondSheet->setCellValue('F'.$eligibilityCell, $eligibility['rating']);
                $secondSheet->setCellValue('G'.$eligibilityCell, date("m/d/Y", strtotime($eligibility['exam_date'])));
                $secondSheet->setCellValue('I'.$eligibilityCell, $eligibility['exam_place']);
                $secondSheet->setCellValue('L'.$eligibilityCell, $eligibility['license_number']);
                $secondSheet->setCellValue('M'.$eligibilityCell, date("m/d/Y", strtotime($eligibility['release_date'])));

                $eligibilityCell++;
            }
        }

        $additionalEligibilities = $model->getCivilServices()->where(['approval' => 'yes'])->orderBy(['exam_date' => SORT_DESC])->offset(7)->limit(14)->asArray()->all();

        $additionalEligibilityCell = 53;

        if(!empty($additionalEligibilities)){
            foreach($additionalEligibilities as $eligibility){
                $secondSheet->setCellValue('A'.$additionalEligibilityCell, $eligibility['eligibility']);
                $secondSheet->setCellValue('F'.$additionalEligibilityCell, $eligibility['rating']);
                $secondSheet->setCellValue('G'.$additionalEligibilityCell, date("m/d/Y", strtotime($eligibility['exam_date'])));
                $secondSheet->setCellValue('I'.$additionalEligibilityCell, $eligibility['exam_place']);
                $secondSheet->setCellValue('L'.$additionalEligibilityCell, $eligibility['license_number']);
                $secondSheet->setCellValue('M'.$additionalEligibilityCell, date("m/d/Y", strtotime($eligibility['release_date'])));

                $eligibilityCell++;
            }
        }

        $workExperiences = $model->getWorkExperiences()->orderBy(['date_start' => SORT_DESC])->limit(29)->asArray()->all();
        
        $workExperienceCell = 18;

        if(!empty($workExperiences)){
            foreach($workExperiences as $workExperience){
                $secondSheet->setCellValue('A'.$workExperienceCell, date("m/d/Y", strtotime($workExperience['date_start'])));
                $secondSheet->setCellValue('C'.$workExperienceCell, (!is_null($workExperience['date_end']) ? date("m/d/Y", strtotime($workExperience['date_end'])) : ''));
                $secondSheet->setCellValue('D'.$workExperienceCell, $workExperience['position']);
                $secondSheet->setCellValue('G'.$workExperienceCell, $workExperience['agency']);
                $secondSheet->setCellValue('J'.$workExperienceCell, $workExperience['monthly_salary']);
                $secondSheet->setCellValue('K'.$workExperienceCell, $workExperience['grade'].'-'.$workExperience['step']);
                $secondSheet->setCellValue('L'.$workExperienceCell, $workExperience['appointment']);
                $secondSheet->setCellValue('M'.$workExperienceCell, $workExperience['type']);

                $workExperienceCell++;
            }
        }

        $additionalWorkExperiences = $model->getWorkExperiences()->orderBy(['date_start' => SORT_DESC])->offset(29)->asArray()->all();
        
        $additionalWorkExperienceCell = 72;

        if(!empty($additionalWorkExperiences)){
            foreach($additionalWorkExperiences as $workExperience){
                $secondSheet->setCellValue('A'.$additionalWorkExperienceCell, date("m/d/Y", strtotime($workExperience['date_start'])));
                $secondSheet->setCellValue('C'.$additionalWorkExperienceCell, (!is_null($workExperience['date_end']) ? date("m/d/Y", strtotime($workExperience['date_end'])) : ''));
                $secondSheet->setCellValue('D'.$additionalWorkExperienceCell, $workExperience['position']);
                $secondSheet->setCellValue('G'.$additionalWorkExperienceCell, $workExperience['agency']);
                $secondSheet->setCellValue('J'.$additionalWorkExperienceCell, $workExperience['monthly_salary']);
                $secondSheet->setCellValue('K'.$additionalWorkExperienceCell, $workExperience['grade'].'-'.$workExperience['step']);
                $secondSheet->setCellValue('L'.$additionalWorkExperienceCell, $workExperience['appointment']);
                $secondSheet->setCellValue('M'.$additionalWorkExperienceCell, $workExperience['type']);

                $additionalWorkExperienceCell++;
            }
        }

        $thirdSheet = $spreadsheet->getSheetByName('C3');

        $voluntaryWorks = $model->getVoluntaryWorks()->where(['approval' => 'yes'])->orderBy(['from_date' => SORT_DESC])->limit(7)->asArray()->all();
        
        $voluntaryWorkCell = 6;

        if(!empty($voluntaryWorks)){
            foreach($voluntaryWorks as $voluntaryWork){
                $thirdSheet->setCellValue('A'.$voluntaryWorkCell, $voluntaryWork['name_add_org']);
                $thirdSheet->setCellValue('E'.$voluntaryWorkCell, date("m/d/Y", strtotime($voluntaryWork['from_date'])));
                $thirdSheet->setCellValue('F'.$voluntaryWorkCell, (!is_null($voluntaryWork['to_date']) ? date("m/d/Y", strtotime($voluntaryWork['to_date'])) : ''));
                $thirdSheet->setCellValue('G'.$voluntaryWorkCell, $voluntaryWork['hours']);
                $thirdSheet->setCellValue('H'.$voluntaryWorkCell, $voluntaryWork['nature_of_work']);

                $voluntaryWorkCell++;
            }
        }

        $additionalVoluntaryWorks = $model->getVoluntaryWorks()->where(['approval' => 'yes'])->orderBy(['from_date' => SORT_DESC])->offset(7)->limit(20)->asArray()->all();

        $additionalVoluntaryWorkCell = 63;

        if(!empty($additionalVoluntaryWorks)){
            foreach($additionalVoluntaryWorks as $voluntaryWork){
                $thirdSheet->setCellValue('A'.$additionalVoluntaryWorkCell, $voluntaryWork['name_add_org']);
                $thirdSheet->setCellValue('E'.$additionalVoluntaryWorkCell, date("m/d/Y", strtotime($voluntaryWork['from_date'])));
                $thirdSheet->setCellValue('F'.$additionalVoluntaryWorkCell, (!is_null($voluntaryWork['to_date']) ? date("m/d/Y", strtotime($voluntaryWork['to_date'])) : ''));
                $thirdSheet->setCellValue('G'.$additionalVoluntaryWorkCell, $voluntaryWork['hours']);
                $thirdSheet->setCellValue('H'.$additionalVoluntaryWorkCell, $voluntaryWork['nature_of_work']);

                $additionalVoluntaryWorkCell++;
            }
        }

        $trainings = $model->getTrainings()->where(['approval' => 'yes'])->orderBy(['from_date' => SORT_DESC])->limit(25)->asArray()->all();

        $trainingCell = 19;

        if(!empty($trainings)){
            foreach($trainings as $training){

                $thirdSheet->setCellValue('A'.$trainingCell, $training['seminar_title']);
                $thirdSheet->setCellValue('E'.$trainingCell, date("m/d/Y", strtotime($training['from_date'])));
                $thirdSheet->setCellValue('F'.$trainingCell, (!is_null($training['to_date']) ? date("m/d/Y", strtotime($training['to_date'])) : ''));
                $thirdSheet->setCellValue('G'.$trainingCell, $training['hours']);
                $thirdSheet->setCellValue('H'.$trainingCell, $training['category']);
                $thirdSheet->setCellValue('I'.$trainingCell, $training['sponsor']);

                $trainingCell++;
            }
        }

        $additionalTrainings = $model->getTrainings()->where(['approval' => 'yes'])->orderBy(['from_date' => SORT_DESC])->offset(25)->limit(109)->asArray()->all();

        $additionalTrainingCell = 87;

        if(!empty($additionalTrainings)){
            foreach($additionalTrainings as $training){

                $thirdSheet->setCellValue('A'.$additionalTrainingCell, $training['seminar_title']);
                $thirdSheet->setCellValue('E'.$additionalTrainingCell, date("m/d/Y", strtotime($training['from_date'])));
                $thirdSheet->setCellValue('F'.$additionalTrainingCell, (!is_null($training['to_date']) ? date("m/d/Y", strtotime($training['to_date'])) : ''));
                $thirdSheet->setCellValue('G'.$additionalTrainingCell, $training['hours']);
                $thirdSheet->setCellValue('H'.$additionalTrainingCell, $training['category']);
                $thirdSheet->setCellValue('I'.$additionalTrainingCell, $training['sponsor']);

                $additionalTrainingCell++;
            }
        }

        $skills = $model->getOtherInformations()->where(['approval' => 'yes', 'type' => 'hobbies'])->orderBy(['description' => SORT_ASC])->limit(7)->asArray()->all();

        $skillCell = 47;

        if(!empty($skills)){
            foreach($skills as $skill){

                $thirdSheet->setCellValue('A'.$skillCell, $skill['description']);

                $skillCell++;
            }
        }

        $additionalSkills = $model->getOtherInformations()->where(['approval' => 'yes', 'type' => 'hobbies'])->orderBy(['description' => SORT_ASC])->offset(7)->asArray()->all();

        $additionalSkillCell = 198;

        if(!empty($additionalSkills)){
            foreach($additionalSkills as $skill){

                $thirdSheet->setCellValue('A'.$additionalSkillCell, $skill['description']);

                $additionalSkillCell++;
            }
        }

        $recognitions = $model->getOtherInformations()->where(['approval' => 'yes', 'type' => 'recognition'])->orderBy(['description' => SORT_ASC])->limit(7)->asArray()->all();

        $recognitionCell = 47;

        if(!empty($recognitions)){
            foreach($recognitions as $recognition){

                $thirdSheet->setCellValue('C'.$recognitionCell, $recognition['description']);

                $recognitionCell++;
            }
        }

        $additionalRecognitions = $model->getOtherInformations()->where(['approval' => 'yes', 'type' => 'recognition'])->orderBy(['description' => SORT_ASC])->offset(7)->asArray()->all();

        $additionalRecognitionCell = 198;

        if(!empty($additionalRecognitions)){
            foreach($additionalRecognitions as $recognition){

                $thirdSheet->setCellValue('C'.$additionalRecognitionCell, $recognition['description']);

                $additionalRecognitionCell++;
            }
        }

        $organizations = $model->getOtherInformations()->where(['approval' => 'yes', 'type' => 'membership'])->orderBy(['description' => SORT_ASC])->limit(7)->asArray()->all();

        $organizationCell = 47;

        if(!empty($organizations)){
            foreach($organizations as $organization){

                $thirdSheet->setCellValue('I'.$organizationCell, $organization['description']);

                $organizationCell++;
            }
        }

        $additionalOrganizations = $model->getOtherInformations()->where(['approval' => 'yes', 'type' => 'membership'])->orderBy(['description' => SORT_ASC])->offset(7)->asArray()->all();

        $additionalOrganizationCell = 198;

        if(!empty($additionalOrganizations)){
            foreach($additionalOrganizations as $organization){

                $thirdSheet->setCellValue('I'.$additionalOrganizationCell, $organization['description']);

                $additionalOrganizationCell++;
            }
        }

        $fourthSheet = $spreadsheet->getSheetByName('C4');

        $q1A = $model->getQuestions()->where(['number' => 36, 'list' => 'A'])->one();

        if($q1A && $q1A->answer == 'true'){
            $fourthSheet->setCellValue('G6', '☑️ YES        ☐ NO'); 
        }else if($q1A && $q1A->answer == 'false'){
            $fourthSheet->setCellValue('G6', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G6', '☐ YES         ☐ NO');
        }

        $q1B = $model->getQuestions()->where(['number' => 36, 'list' => 'B'])->one();

        if($q1B && $q1B->answer == 'true'){
            $fourthSheet->setCellValue('G8', '☑️ YES        ☐ NO'); 
            $fourthSheet->setCellValue('H11', $q1B->yes_details); 
        }else if($q1B && $q1B->answer == 'false'){
            $fourthSheet->setCellValue('G8', '☐ YES        ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G8', '☐ YES        ☐ NO');
        }

        $q2A = $model->getQuestions()->where(['number' => 37, 'list' => 'B'])->one();

        if($q2A && $q2A->answer == 'true'){
            $fourthSheet->setCellValue('G13', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('H15', $q2A->yes_details); 
        }else if($q2A && $q2A->answer == 'false'){
            $fourthSheet->setCellValue('G13', '☐ YES        ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G13', '☐ YES        ☐ NO');
        }

        $q2B = $model->getQuestions()->where(['number' => 37, 'list' => 'A'])->one();

        if($q2B && $q2B->answer == 'true'){
            $fourthSheet->setCellValue('G18', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('K20', $q2B->yes_details); 
            $fourthSheet->setCellValue('K21', $q2B->yes_details); 
        }else if($q2B && $q2B->answer == 'false'){
            $fourthSheet->setCellValue('G18', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G18', '☐ YES         ☐ NO');
        }

        $q3 = $model->getQuestions()->where(['number' => 38, 'list' => 'NA'])->one();

        if($q3 && $q3->answer == 'true'){
            $fourthSheet->setCellValue('G23', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('H25', $q3->yes_details); 
        }else if($q3 && $q3->answer == 'false'){
            $fourthSheet->setCellValue('G23', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G23', '☐ YES         ☐ NO');
        }

        $q4 = $model->getQuestions()->where(['number' => 39, 'list' => 'NA'])->one();

        if($q4 && $q4->answer == 'true'){
            $fourthSheet->setCellValue('G27', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('H29', $q4->yes_details); 
        }else if($q4 && $q4->answer == 'false'){
            $fourthSheet->setCellValue('G27', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G27', '☐ YES         ☐ NO');
        }

        $q5A = $model->getQuestions()->where(['number' => 40, 'list' => 'NA'])->one();

        if($q5A && $q5A->answer == 'true'){
            $fourthSheet->setCellValue('G31', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('K32', $q5A->yes_details); 
        }else if($q5A && $q5A->answer == 'false'){
            $fourthSheet->setCellValue('G31', '☐ YES        ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G31', '☐ YES        ☐ NO');
        }

        $q5B = $model->getQuestions()->where(['number' => 40, 'list' => 'B'])->one();

        if($q5B && $q5B->answer == 'true'){
            $fourthSheet->setCellValue('G34', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('K35', $q5B->yes_details); 
        }else if($q5B && $q5B->answer == 'false'){
            $fourthSheet->setCellValue('G34', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G34', '☐ YES         ☐ NO');
        }

        $q6 = $model->getQuestions()->where(['number' => 42, 'list' => 'NA'])->one();

        if($q6 && $q6->answer == 'true'){
            $fourthSheet->setCellValue('G37', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('H39', $q6->yes_details); 
        }else if($q6 && $q6->answer == 'false'){
            $fourthSheet->setCellValue('G37', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G37', '☐ YES         ☐ NO');
        }

        $q7A = $model->getQuestions()->where(['number' => 41, 'list' => 'A'])->one();

        if($q7A && $q7A->answer == 'true'){
            $fourthSheet->setCellValue('G43', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('L44', $q7A->yes_details); 
        }else if($q7A && $q7A->answer == 'false'){
            $fourthSheet->setCellValue('G43', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G43', '☐ YES         ☐ NO');
        }

        $q7B = $model->getQuestions()->where(['number' => 41, 'list' => 'B'])->one();

        if($q7B && $q7B->answer == 'true'){
            $fourthSheet->setCellValue('G45', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('L46', $q7B->yes_details); 
        }else if($q7B && $q7B->answer == 'false'){
            $fourthSheet->setCellValue('G45', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G45', '☐ YES         ☐ NO');
        }

        $q7C = $model->getQuestions()->where(['number' => 41, 'list' => 'C'])->one();

        if($q7C && $q7C->answer == 'true'){
            $fourthSheet->setCellValue('G47', '☑️ YES        ☐ NO');
            $fourthSheet->setCellValue('L48', $q7C->yes_details); 
        }else if($q7C && $q7C->answer == 'false'){
            $fourthSheet->setCellValue('G47', '☐ YES         ☑️ NO');
        }else{
            $fourthSheet->setCellValue('G47', '☐ YES         ☐ NO');
        }

        $references = $model->getReferences()->orderBy(['ref_name' => SORT_ASC])->limit(3)->asArray()->all();

        $referenceCell = 52;

        if(!empty($references)){
            foreach($references as $reference){

                $fourthSheet->setCellValue('A'.$referenceCell, $reference['ref_name']);
                $fourthSheet->setCellValue('F'.$referenceCell, $reference['address']);
                $fourthSheet->setCellValue('G'.$referenceCell, $reference['tel_no']);

                $referenceCell++;
            }
        }
        
        $tempFilePath = Yii::getAlias('@frontend') . '/web/temp/';
        $filename = date("YmdHis").'_'.$model->emp_id.'_pds.xlsx';
        $spreadsheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFilePath . $filename);

        // Offer the Excel file for download
        return Yii::$app->response->sendFile($tempFilePath . $filename, $filename, [
            'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'inline' => false,
        ])->send();
    }

    public function actionNotify($id, $content)
    {
        if(Yii::$app->request->post())
        {
            $model = Employee::findOne(['emp_id' => $id]);
            $mailer = Yii::$app->mailer;
            $hrRole = Yii::$app->authManager->getRole('HR');

            if ($hrRole !== null) {
                // Get all users assigned to the 'HR' role
                $HRs = User::find()
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->where(['auth_assignment.item_name' => $hrRole->name])
                ->all();

                $emails = [];

                if($HRs){
                    foreach($HRs as $HR){
                        $emails[] = $HR->email;
                    }
                }
            }

            if($content == 'Education'){
                $subject = 'educational background';
            }else if($content == 'Eligibility'){
                $subject = 'civil service eligibility';
            }else if($content == 'Voluntary Work'){
                $subject = 'voluntary work';
            }else if($content == 'Training'){
                $subject = 'learning and development interventions / trainings attended';
            }else if($content == 'Skill'){
                $subject = 'special skills and hobbies';
            }else if($content == 'Recognition'){
                $subject = 'non-academic distinctions/recognitions';
            }else if($content == 'Organization'){
                $subject = 'membership in associations/organization';
            }

            $message = $mailer->compose('review-pds-html', [
                'model' => $model,
                'content' => $content,
                'title' => $subject,
            ])
            ->setFrom('nro1.mailer@neda.gov.ph')
            ->setTo($emails)
            ->setSubject('IPMS Notification: Request to review and approve entries in '.$subject.' of '.($model->gender == 'Male' ? 'Mr.' : 'Ms.').' '.$model->fname.' '.$model->mname.' '.$model->lname);

            $transaction = Yii::$app->ipms->beginTransaction();
            try {
                if ($message->send()) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Notification has been sent successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while sending notification');
            }
        }
    }

    public function actionReview($id, $content)
    {
        $model = Employee::findOne(['emp_id' => $id]);

        if($content == 'Education'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab2");
        }else if($content == 'Eligibility'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab3");
        }else if($content == 'Voluntary Work'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab5");
        }else if($content == 'Training'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab6");
        }else if($content == 'Skill'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab7");
            Yii::$app->session->set('otherLastTab', "#other-information-tabs-tab0");
        }else if($content == 'Recognition'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab7");
            Yii::$app->session->set('otherLastTab', "#other-information-tabs-tab1");
        }else if($content == 'Organization'){
            Yii::$app->session->set('staffPdsLastTab', "#staff-pds-tabs-tab7");
            Yii::$app->session->set('otherLastTab', "#other-information-tabs-tab2");
        }

        Yii::$app->session->set('selectedStaffProfile', $model->emp_id);
        
        $this->redirect(['/npis/pds/']);
    }
}