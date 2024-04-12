<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\EmployeeServiceContract;
use common\modules\npis\models\EmployeeServiceContractId;
use common\modules\npis\models\EmployeeServiceContractSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ServiceContractController implements the CRUD actions for ServiceContract model.
 */
class ServiceContractController extends Controller
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
                        'roles' => ['service-contract-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['service-contract-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['service-contract-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['service-contract-delete'],
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
        $searchModel = new EmployeeServiceContractSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $serviceContractModels = [];

        $serviceContracts = EmployeeServiceContract::find()->orderBy([
            'from_date' => SORT_DESC,
            'service' => SORT_ASC
        ])->all();

        if($serviceContracts){
            foreach($serviceContracts as $idx => $serviceContract){
                $serviceContractModels[$idx + 1] = $serviceContract;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeServiceContract');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedServiceContracts = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedServiceContracts[] = $serviceContractModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedServiceContracts)){
                    foreach($selectedServiceContracts as $selectedServiceContract){
                        $serviceContractId = EmployeeServiceContractId::findOne([
                            'emp_id' => $selectedServiceContract->emp_id,
                            'service' => $selectedServiceContract->service,
                            'from_date' => $selectedServiceContract->from_date,
                        ]);

                        if($serviceContractId){
                            $serviceContractId->delete();
                        }
                        $selectedServiceContract->delete();
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
            'serviceContractModels' => $serviceContractModels,
        ]);
    }

    /**
     * Creates a new Ipcr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeServiceContract();
        $idModel = new EmployeeServiceContractId();
        
        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->service = $model->service;
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
    public function actionUpdate($emp_id, $service, $from_date)
    {
        $model = EmployeeServiceContract::findOne([
            'emp_id' => $emp_id,
            'service' => $service,
            'from_date' => $from_date,
        ]);

        $idModel = EmployeeServiceContractId::findOne([
            'emp_id' => $emp_id,
            'service' => $service,
            'from_date' => $from_date,
        ]) ? EmployeeServiceContractId::findOne([
            'emp_id' => $emp_id,
            'service' => $service,
            'from_date' => $from_date,
        ]) : new EmployeeServiceContractId();

        $idModel->emp_id = $model->emp_id;
        $idModel->service = $model->service;
        $idModel->from_date = $model->from_date;

        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->service = $model->service;
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
