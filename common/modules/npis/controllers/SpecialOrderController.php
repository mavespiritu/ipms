<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\EmployeeSpecialOrder;
use common\modules\npis\models\EmployeeSpecialOrderId;
use common\modules\npis\models\EmployeeSpecialOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * SpecialOrderController implements the CRUD actions for SpecialOrder model.
 */
class SpecialOrderController extends Controller
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
                        'roles' => ['special-order-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['special-order-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['special-order-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['special-order-delete'],
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
        $searchModel = new EmployeeSpecialOrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $specialOrderModels = [];

        $specialOrders = EmployeeSpecialOrder::find()->orderBy([
            'from_date' => SORT_DESC,
            'subject' => SORT_ASC
        ])->all();

        if($specialOrders){
            foreach($specialOrders as $idx => $specialOrder){
                $specialOrderModels[$idx + 1] = $specialOrder;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeSpecialOrder');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedSpecialOrders = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedSpecialOrders[] = $specialOrderModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedSpecialOrders)){
                    foreach($selectedSpecialOrders as $selectedSpecialOrder){
                        $specialOrderId = EmployeeSpecialOrderId::findOne([
                            'emp_id' => $selectedSpecialOrder->emp_id,
                            'subject' => $selectedSpecialOrder->subject,
                            'from_date' => $selectedSpecialOrder->from_date,
                        ]);

                        if($specialOrderId){
                            $specialOrderId->delete();
                        }
                        $selectedSpecialOrder->delete();
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
            'specialOrderModels' => $specialOrderModels,
        ]);
    }

    /**
     * Creates a new Ipcr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeSpecialOrder();
        $idModel = new EmployeeSpecialOrderId();
        
        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->subject = $model->subject;
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
    public function actionUpdate($emp_id, $subject, $from_date)
    {
        $model = EmployeeSpecialOrder::findOne([
            'emp_id' => $emp_id,
            'subject' => $subject,
            'from_date' => $from_date,
        ]);

        $idModel = EmployeeSpecialOrderId::findOne([
            'emp_id' => $emp_id,
            'subject' => $subject,
            'from_date' => $from_date,
        ]) ? EmployeeSpecialOrderId::findOne([
            'emp_id' => $emp_id,
            'subject' => $subject,
            'from_date' => $from_date,
        ]) : new EmployeeSpecialOrderId();

        $idModel->emp_id = $model->emp_id;
        $idModel->subject = $model->subject;
        $idModel->from_date = $model->from_date;

        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->subject = $model->subject;
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
