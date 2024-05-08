<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\Competency;
use common\modules\npis\models\CompetencyIndicator;
use common\modules\npis\models\CompetencyIndicatorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * IndicatorController implements the CRUD actions for CompetencyIndicator model.
 */
class IndicatorController extends Controller
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
                        'roles' => ['competency-indicator-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['competency-indicator-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['competency-indicator-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['competency-indicator-delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CompetencyIndicator models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompetencyIndicatorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $indicatorModels = [];
        
        $indicators = CompetencyIndicator::find()->all();

        $competencies = Competency::find()->all();
        $competencies = ArrayHelper::map($competencies, 'comp_id', 'competency');

        if($indicators){
            foreach($indicators as $indicator){
                $indicatorModels[$indicator->id] = $indicator;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('CompetencyIndicator');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedIndexes)){
                    if(CompetencyIndicator::deleteAll(['id' => $selectedIndexes]))
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
            'indicatorModels' => $indicatorModels,
            'competencies' => $competencies,
        ]);
    }

    /**
     * Displays a single CompetencyIndicator model.
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
     * Creates a new CompetencyIndicator model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CompetencyIndicator();

        $competencies = Competency::find()->all();
        $competencies = ArrayHelper::map($competencies, 'comp_id', 'competency');

        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Saved');
                return $this->redirect(['index']);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'competencies' => $competencies,
        ]);
    }

    /**
     * Updates an existing CompetencyIndicator model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $competencies = Competency::find()->all();
        $competencies = ArrayHelper::map($competencies, 'comp_id', 'competency');

        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Updated');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'competencies' => $competencies,
        ]);
    }

    /**
     * Deletes an existing CompetencyIndicator model.
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
     * Finds the CompetencyIndicator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CompetencyIndicator the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CompetencyIndicator::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
