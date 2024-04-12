<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\EmployeeNbiClearance;
use common\modules\npis\models\EmployeeNbiClearanceId;
use common\modules\npis\models\EmployeeNbiClearanceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * NbiClearanceController implements the CRUD actions for NbiClearance model.
 */
class NbiClearanceController extends Controller
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
                        'roles' => ['nbi-clearance-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['nbi-clearance-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['nbi-clearance-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['nbi-clearance-delete'],
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
        $searchModel = new EmployeeNbiClearanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $nbiClearanceModels = [];

        $nbiClearances = EmployeeNbiClearance::find()->orderBy([
            'from_date' => SORT_DESC,
        ])->all();

        if($nbiClearances){
            foreach($nbiClearances as $idx => $nbiClearance){
                $nbiClearanceModels[$idx + 1] = $nbiClearance;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeNbiClearance');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedNbiClearances = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedNbiClearances[] = $nbiClearanceModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedNbiClearances)){
                    foreach($selectedNbiClearances as $selectedNbiClearance){
                        $nbiClearanceId = EmployeeNbiClearanceId::findOne([
                            'emp_id' => $selectedNbiClearance->emp_id,
                            'from_date' => $selectedNbiClearance->from_date,
                        ]);

                        if($nbiClearanceId){
                            $nbiClearanceId->delete();
                        }
                        $selectedNbiClearance->delete();
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
            'nbiClearanceModels' => $nbiClearanceModels,
        ]);
    }

    /**
     * Creates a new Ipcr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeNbiClearance();
        $idModel = new EmployeeNbiClearanceId();
        
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
        $model = EmployeeNbiClearance::findOne([
            'emp_id' => $emp_id,
            'from_date' => $from_date,
        ]);

        $idModel = EmployeeNbiClearanceId::findOne([
            'emp_id' => $emp_id,
            'from_date' => $from_date,
        ]) ? EmployeeNbiClearanceId::findOne([
            'emp_id' => $emp_id,
            'from_date' => $from_date,
        ]) : new EmployeeNbiClearanceId();

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
