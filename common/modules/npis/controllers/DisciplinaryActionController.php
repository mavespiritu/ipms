<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\EmployeeDisciplinaryAction;
use common\modules\npis\models\EmployeeDisciplinaryActionId;
use common\modules\npis\models\EmployeeDisciplinaryActionSearch;
use common\modules\npis\models\Deviance;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * DisciplinaryActionController implements the CRUD actions for DisciplinaryAction model.
 */
class DisciplinaryActionController extends Controller
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
                        'roles' => ['disciplinary-action-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['disciplinary-action-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['disciplinary-action-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['disciplinary-action-delete'],
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
        $searchModel = new EmployeeDisciplinaryActionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $disciplinaryActionModels = [];

        $disciplinaryActions = EmployeeDisciplinaryAction::find()->orderBy([
            'from_date' => SORT_DESC,
            'deviance' => SORT_ASC
        ])->all();

        if($disciplinaryActions){
            foreach($disciplinaryActions as $idx => $disciplinaryAction){
                $disciplinaryActionModels[$idx + 1] = $disciplinaryAction;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeDisciplinaryAction');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedDisciplinaryActions = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedDisciplinaryActions[] = $disciplinaryActionModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedDisciplinaryActions)){
                    foreach($selectedDisciplinaryActions as $selectedDisciplinaryAction){
                        $disciplinaryActionId = EmployeeDisciplinaryActionId::findOne([
                            'emp_id' => $selectedDisciplinaryAction->emp_id,
                            'deviance' => $selectedDisciplinaryAction->deviance,
                            'from_date' => $selectedDisciplinaryAction->from_date,
                        ]);

                        if($disciplinaryActionId){
                            $disciplinaryActionId->delete();
                        }
                        $selectedDisciplinaryAction->delete();
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
            'disciplinaryActionModels' => $disciplinaryActionModels,
        ]);
    }

    /**
     * Creates a new Ipcr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeDisciplinaryAction();
        $idModel = new EmployeeDisciplinaryActionId();

        $deviances = Deviance::find()->all();
        $deviances = ArrayHelper::map($deviances, 'deviance', 'deviance');
        
        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->deviance = $model->deviance;
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
            'idModel' => $idModel,
            'deviances' => $deviances,
        ]);
    }

    /**
     * Updates an existing Ipcr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($emp_id, $deviance, $from_date)
    {
        $model = EmployeeDisciplinaryAction::findOne([
            'emp_id' => $emp_id,
            'deviance' => $deviance,
            'from_date' => $from_date,
        ]);

        $idModel = EmployeeDisciplinaryActionId::findOne([
            'emp_id' => $emp_id,
            'deviance' => $deviance,
            'from_date' => $from_date,
        ]) ? EmployeeDisciplinaryActionId::findOne([
            'emp_id' => $emp_id,
            'deviance' => $deviance,
            'from_date' => $from_date,
        ]) : new EmployeeDisciplinaryActionId();

        $idModel->emp_id = $model->emp_id;
        $idModel->deviance = $model->deviance;
        $idModel->from_date = $model->from_date;

        $deviances = Deviance::find()->all();
        $deviances = ArrayHelper::map($deviances, 'deviance', 'deviance');

        if ($model->load(Yii::$app->request->post())) {

            $idModel->emp_id = $model->emp_id;
            $idModel->deviance = $model->deviance;
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
            'deviances' => $deviances,
        ]);
    }
}
