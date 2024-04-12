<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\EmployeeOtherDocument;
use common\modules\npis\models\EmployeeOtherDocumentId;
use common\modules\npis\models\EmployeeOtherDocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * OtherDocumentController implements the CRUD actions for OtherDocument model.
 */
class OtherDocumentController extends Controller
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
                        'roles' => ['other-document-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['other-document-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['other-document-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['other-document-delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Other Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeOtherDocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $otherDocumentModels = [];

        $otherDocuments = EmployeeOtherDocument::find()->orderBy([
            'from_date' => SORT_DESC,
            'subject' => SORT_ASC
        ])->all();

        if($otherDocuments){
            foreach($otherDocuments as $idx => $otherDocument){
                $otherDocumentModels[$idx + 1] = $otherDocument;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('EmployeeOtherDocument');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);
            $selectedOtherDocuments = [];

            if(!empty($selectedIndexes))
            {
                foreach($selectedIndexes as $idx)
                {
                    $selectedOtherDocuments[] = $otherDocumentModels[$idx];
                }
            }

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedOtherDocuments)){
                    foreach($selectedOtherDocuments as $selectedOtherDocument){
                        $otherDocumentId = EmployeeOtherDocumentId::findOne([
                            'emp_id' => $selectedOtherDocument->emp_id,
                            'subject' => $selectedOtherDocument->subject,
                            'from_date' => $selectedOtherDocument->from_date,
                        ]);

                        if($otherDocumentId){
                            $otherDocumentId->delete();
                        }
                        $selectedOtherDocument->delete();
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
            'otherDocumentModels' => $otherDocumentModels,
        ]);
    }

    /**
     * Creates a new Ipcr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeOtherDocument();
        $idModel = new EmployeeOtherDocumentId();
        
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
        $model = EmployeeOtherDocument::findOne([
            'emp_id' => $emp_id,
            'subject' => $subject,
            'from_date' => $from_date,
        ]);

        $idModel = EmployeeOtherDocumentId::findOne([
            'emp_id' => $emp_id,
            'subject' => $subject,
            'from_date' => $from_date,
        ]) ? EmployeeOtherDocumentId::findOne([
            'emp_id' => $emp_id,
            'subject' => $subject,
            'from_date' => $from_date,
        ]) : new EmployeeOtherDocumentId();

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
