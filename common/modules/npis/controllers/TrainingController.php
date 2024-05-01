<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\LearningServiceProvider;
use common\modules\npis\models\Training;
use common\modules\npis\models\TrainingCompetency;
use common\modules\npis\models\Competency;
use common\modules\npis\models\TrainingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * TrainingController implements the CRUD actions for Training model.
 */
class TrainingController extends Controller
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
                        'roles' => ['training-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['training-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['training-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['training-delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Training models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrainingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $trainingModels = [];
        
        $trainings = Training::find()->all();

        $lsps = LearningServiceProvider::find()->all();
        $lsps = ArrayHelper::map($lsps, 'id', 'lsp_name');

        $competencies = Competency::find()->all();
        $competencies = ArrayHelper::map($lsps, 'comp_id', 'competency');

        if($trainings){
            foreach($trainings as $training){
                $trainingModels[$training->id] = $training;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('Training');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedIndexes)){
                    if(Training::deleteAll(['id' => $selectedIndexes]))
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
            'trainingModels' => $trainingModels,
            'lsps' => $lsps,
            'competencies' => $competencies,
        ]);
    }

    /**
     * Displays a single Training model.
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
     * Creates a new Training model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Training();

        $competencyModel = new TrainingCompetency();

        $lsps = LearningServiceProvider::find()->all();
        $lsps = ArrayHelper::map($lsps, 'id', 'lsp_name');

        $competencies = Competency::find()->all();
        $competencies = ArrayHelper::map($competencies, 'comp_id', 'competency');

        if ($model->load(Yii::$app->request->post()) &&
            $competencyModel->load(Yii::$app->request->post())) {
            
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {

                    if(!empty($competencyModel->competency_id))
                    {
                        foreach($competencyModel->competency_id as $id)
                        {
                            $competency = new TrainingCompetency();
                            $competency->training_id = $model->id;
                            $competency->competency_id = $id;
                            if (! ($flag = $competency->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        \Yii::$app->getSession()->setFlash('success', 'Record Saved');
                        return $this->redirect(['/npis/training/']);
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
            'competencyModel' => $competencyModel,
            'lsps' => $lsps,
            'competencies' => $competencies
        ]);
    }

    /**
     * Updates an existing Training model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $competencyModel = new TrainingCompetency();
        $oldCompetencies = $model->competencies;
        $competencyModel->competency_id = array_values(ArrayHelper::map($oldCompetencies, 'competency_id', 'competency_id'));

        $lsps = LearningServiceProvider::find()->all();
        $lsps = ArrayHelper::map($lsps, 'id', 'lsp_name');

        $competencies = Competency::find()->all();
        $competencies = ArrayHelper::map($competencies, 'comp_id', 'competency');

        if ($model->load(Yii::$app->request->post()) &&
            $competencyModel->load(Yii::$app->request->post())) {

            $oldCompetencyIDs = array_values(ArrayHelper::map($oldCompetencies, 'competency_id', 'competency_id'));
            $deletedCompetencyIDs = $competencyModel->competency_id != '' ? array_diff($oldCompetencyIDs, array_filter($competencyModel->competency_id)) : array_diff($oldCompetencyIDs, []);

            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {
                    if(!empty($deletedCompetencyIDs))
                    {
                        TrainingCompetency::deleteAll(['training_id' => $model->id, 'competency_id' => $deletedCompetencyIDs]);
                    }

                    if(!empty($competencyModel->competency_id))
                    {
                        foreach($competencyModel->competency_id as $id)
                        {
                            $competency = TrainingCompetency::findOne(['training_id' => $model->id, 'competency_id' => $id]) ?
                            TrainingCompetency::findOne(['training_id' => $model->id, 'competency_id' => $id]) : new TrainingCompetency();
                            $competency->training_id = $model->id;
                            $competency->competency_id = $id;
                            if (! ($flag = $competency->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Record Updated');
                    return $this->redirect(['/npis/training/']);
                }
                
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('update', [
            'model' => $model,
            'competencyModel' => $competencyModel,
            'lsps' => $lsps,
            'competencies' => $competencies
        ]);
    }

    /**
     * Deletes an existing Training model.
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
     * Finds the Training model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Training the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Training::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
