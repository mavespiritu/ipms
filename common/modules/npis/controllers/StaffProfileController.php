<?php

namespace common\modules\npis\controllers;

use Yii;
use common\models\Employee;
use common\models\Division;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * SpecialOrderController implements the CRUD actions for SpecialOrder model.
 */
class StaffProfileController extends Controller
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
                        'roles' => ['staff-profile-view'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Ipcr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $connection = Yii::$app->ipms;

        $fields = [
            'basic_information' => [
                'lname' => 'Last Name',
                'fname' => 'First Name',
                'mname' => 'Middle Name',
                'civil_status' => 'Civil Status',
                'birth_place' => 'Birth Place',
                'birth_date' => 'Birth Date',
                'age' => 'Age',
                'gender' => 'Gender',
                'citizenship' => 'Citizenship',
                'height' => 'Height (m)',
                'weight' => 'Weight (kg)',
                'blood_type' => 'Blood Type',
                'cell_no' => 'Mobile No.',
                'e_mail_add' => 'Email Address',
                'residential_address' => 'Residential Address',
                'residential_zip_code' => 'Residential Zip Code',
                'residential_tel_no' => 'Residential Tel No.',
                'permanent_address' => 'Permanent Address',
                'permanent_zip_code' => 'Permanent Zip Code',
                'permanent_tel_no' => 'Permanent Tel No.',
                'picture' => 'Picture',
                'Pag_ibig' => 'PAG-IBIG No.',
                'GSIS' => 'GSIS No.',
                'TIN' => 'TIN',
                'Philhealth' => 'PhilHealth No.',
                'SSS' => 'SSS No.',
            ],
            'family' => [
                'children' => 'Children',
                'spouse_surname' => 'Spouse\'s Last Name',
                'spouse_firstname' => 'Spouse\'s First Name',
                'spouse_middlename' => 'Spouse\'s Middle Name',
                'spouse_occupation' => 'Spouse\'s Occupation',
                'children' => 'Children',
                'father_surname' => 'Father\'s Last Name',
                'father_firstname' => 'Father\'s First Name',
                'father_middlename' => 'Father\'s Middle Name',
                'father_birthday' => 'Father\'s Birthday',
                'mother_surname' => 'Mother\'s Last Name',
                'mother_firstname' => 'Mother\'s First Name',
                'mother_middlename' => 'Mother\'s Middle Name',
                'mother_birthday' => 'Mother\'s Birthday',
            ],
            'profile' => [
                'educations' => 'Educational Attainment',
                'trainings' => 'Learning and Development',
                'eligibilities' => 'CSC Eligibility',
            ],
            'work_related' => [
                'emp_type_id' => 'Employment Type',
                'position_id' => 'Position',
                'government_date' => 'Date hired in the Gov\'t',
                'years_in_gov' => 'Years in Gov\'t',
                'hire_date' => 'Date Hired',
                'years_in_neda' => 'Years in NEDA',
                'deviances' => 'Deviances',
                'npes_rating' => 'NPES Rating',
                'references' => 'References',
                'service_contracts' => 'Service Contract',
                'special_orders' => 'Special Order',
                'voluntary_works' => 'Voluntary Work',
            ]
        ];

        $divisionsSql = "select a.division_id
                                from tbldivision a, tbldivision_user b
                                where a.division_id = b.division_id
                                and system = 'DTR'
                                order by a.order";

        $divisionsQuery = $connection->createCommand($divisionsSql)
            ->queryAll();

        $divisions = ArrayHelper::map($divisionsQuery, 'division_id', 'division_id');

        return $this->render('index', [
            'fields' => $fields,
            'divisions' => $divisions,
        ]);
    }
}
