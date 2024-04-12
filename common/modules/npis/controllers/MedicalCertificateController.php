<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\EmployeeMedicalCertificate;
use common\modules\npis\models\EmployeeMedicalCertificateId;
use common\modules\npis\models\EmployeeMedicalCertificateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * MedicalCertificateController implements the CRUD actions for MedicalCertificate model.
 */
class MedicalCertificateController extends Controller
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
                        'roles' => ['medical-certificate-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['medical-certificate-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['medical-certificate-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['medical-certificate-delete'],
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
        $searchModel = new EmployeeMedicalCertificateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $medicalCertificateModels = [];

        $medicalCertificates = EmployeeMedicalCertificate::find()->orderBy([
            'from_date' => SORT_DESC,
        ])->all();

        if($medicalCertificates){
            foreach($medicalCertificates as $idx => $medicalCertificate){
                $medicalCertificateModels[$idx + 1] = $medicalCertificate;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeMedicalCertificate');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedMedicalCertificates = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedMedicalCertificates[] = $medicalCertificateModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedMedicalCertificates)){
                    foreach($selectedMedicalCertificates as $selectedMedicalCertificate){
                        $medicalCertificateId = EmployeeMedicalCertificateId::findOne([
                            'emp_id' => $selectedMedicalCertificate->emp_id,
                            'from_date' => $selectedMedicalCertificate->from_date,
                        ]);

                        if($medicalCertificateId){
                            $medicalCertificateId->delete();
                        }
                        $selectedMedicalCertificate->delete();
                    }
                }

                $transaction->commit();
                \Yii::$app->getSession()->setFlash('success', 'Records have been deleted successfully');
                return $this->redirect(['index']);

            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while deleting records');
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'medicalCertificateModels' => $medicalCertificateModels,
        ]);
    }

    /**
     * Creates a new Ipcr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeMedicalCertificate();
        $idModel = new EmployeeMedicalCertificateId();
        
        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->from_date = $model->from_date;
            $idModel->save();

            $model->approval = 'yes';
            $model->approver = Yii::$app->user->identity->userinfo->FIRST_M;
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Saved');
                return $this->redirect(['index']);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'idModel' => $idModel
        ]);
    }

    /**
     * Updates an existing Ipcr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($emp_id, $from_date)
    {
        $model = EmployeeMedicalCertificate::findOne([
            'emp_id' => $emp_id,
            'from_date' => $from_date,
        ]);

        $idModel = EmployeeMedicalCertificateId::findOne([
            'emp_id' => $emp_id,
            'from_date' => $from_date,
        ]) ? EmployeeMedicalCertificateId::findOne([
            'emp_id' => $emp_id,
            'from_date' => $from_date,
        ]) : new EmployeeMedicalCertificateId();

        $idModel->emp_id = $model->emp_id;
        $idModel->from_date = $model->from_date;

        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->from_date = $model->from_date;
            $idModel->save();

            $model->approval = 'yes';
            $model->approver = Yii::$app->user->identity->userinfo->FIRST_M;
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Updated');
                return $this->redirect(['index']);
            }
            
        }

        return $this->render('update', [
            'model' => $model,
            'idModel' => $idModel,
        ]);
    }
}
