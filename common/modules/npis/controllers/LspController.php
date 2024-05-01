<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\LearningServiceProvider;
use common\modules\npis\models\LearningServiceProviderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * LspController implements the CRUD actions for Lsp model.
 */
class LspController extends Controller
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
                        'roles' => ['lsp-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['lsp-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['lsp-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['lsp-delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all lsp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LearningServiceProviderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $lspModels = [];
        
        $lsps = LearningServiceProvider::find()->all();

        if($lsps){
            foreach($lsps as $lsp){
                $lspModels[$lsp->id] = $lsp;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('LearningServiceProvider');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedIndexes)){
                    if(LearningServiceProvider::deleteAll(['id' => $selectedIndexes]))
                    {
                        $transaction->commit();
                        \Yii::$app->getSession()->setFlash('success', 'Records have been deleted successfully');
                        return $this->redirect(['index']);
                    }
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while deleting records');
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'lspModels' => $lspModels,
        ]);
    }

    /**
     * Displays a single lsp model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new lsp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LearningServiceProvider();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Saved');
                return $this->redirect(['index']);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing lsp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Updated');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing lsp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->post()) {
            $this->findModel($id)->delete();
            \Yii::$app->getSession()->setFlash('success', 'Record Deleted');
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the lsp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return lsp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LearningServiceProvider::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
