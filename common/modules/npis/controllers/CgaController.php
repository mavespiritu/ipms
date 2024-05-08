<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\LearningServiceProvider;
use common\modules\npis\models\EmployeeItem;
use common\modules\npis\models\EmployeePositionItem;
use common\modules\npis\models\Training;
use common\modules\npis\models\TrainingCompetency;
use common\modules\npis\models\Competency;
use common\modules\npis\models\CompetencyIndicator;
use common\modules\npis\models\PositionCompetencyIndicator;
use common\modules\npis\models\TrainingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * CgaController implements the CRUD actions for Training model.
 */
class CgaController extends Controller
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
                        'roles' => ['cga-index'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['cga-view'],
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
    public function actionView()
    {
        $model = EmployeeItem::find()
            ->andWhere([
                'emp_id' => Yii::$app->user->identity->userinfo->EMP_N
            ])
            ->andWhere([
                'is', 'to_date', null
            ])
            ->orderBy([
                'from_date' => SORT_DESC
            ])
            ->one();

        $descriptors = $model ? PositionCompetencyIndicator::find()
        ->select([
            'competency_indicator.id as id',
            'competency.competency as competency',
            'competency_indicator.indicator as indicator',
            'competency_indicator.proficiency as proficiency',
            'type' => new \yii\db\Expression('CASE 
                    WHEN competency.comp_type = "org" THEN "Organizational"
                    WHEN competency.comp_type = "mnt" THEN "Managerial"
                    ELSE "Technical/Functional"
                END')
        ])
        ->leftJoin('competency_indicator', 'competency_indicator.id = position_competency_indicator.indicator_id')
        ->leftJoin('competency', 'competency.comp_id = competency_indicator.competency_id')
        ->where([
            'position_id' => $model->item_no
        ])
        ->orderBy([
            'type' => SORT_ASC,
            'competency' => SORT_ASC,
        ])
        ->asArray()
        ->all() : [];

        echo "<pre>"; print_r($descriptors); exit;

        return $this->render('view', [
            'model' => $model
        ]);
    }
}
