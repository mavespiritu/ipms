<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\Competency;
use common\modules\npis\models\CompetencyType;
use common\modules\npis\models\CompetencySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * CompetencyController implements the CRUD actions for Competency model.
 */
class CompetencyController extends Controller
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
                        'roles' => ['competency-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['competency-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['competency-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['competency-delete'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Competency models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompetencySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $competencyModels = [];
        
        $competencies = Competency::find()->all();

        $competencyTypes = CompetencyType::find()->all();
        $competencyTypes = ArrayHelper::map($competencyTypes, 'comp_type', 'competency_type_description');

        if($competencies){
            foreach($competencies as $competency){
                $competencyModels[$competency->comp_id] = $competency;
            }
        }

        if(Yii::$app->request->post())
        {
            $postData = Yii::$app->request->post('Competency');
            $selectedIndexes = ArrayHelper::map($postData, 'comp_id', 'comp_id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);

            $transaction = Yii::$app->ipms->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedIndexes)){
                    if(Competency::deleteAll(['comp_id' => $selectedIndexes]))
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
            'competencyModels' => $competencyModels,
            'competencyTypes' => $competencyTypes,
        ]);
    }

    /**
     * Displays a single Competency model.
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
     * Creates a new Competency model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Competency();

        $competencyTypes = CompetencyType::find()->all();
        $competencyTypes = ArrayHelper::map($competencyTypes, 'comp_type', 'competency_type_description');

        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Saved');
                return $this->redirect(['index']);
            }
            
        }

        return $this->render('create', [
            'model' => $model,
            'competencyTypes' => $competencyTypes,
        ]);
    }

    /**
     * Updates an existing Competency model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $competencyTypes = CompetencyType::find()->all();
        $competencyTypes = ArrayHelper::map($competencyTypes, 'comp_type', 'competency_type_description');

        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
            {
                \Yii::$app->getSession()->setFlash('success', 'Record Updated');
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'competencyTypes' => $competencyTypes,
        ]);
    }

    /**
     * Deletes an existing Competency model.
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
     * Finds the Competency model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Competency the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Competency::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
