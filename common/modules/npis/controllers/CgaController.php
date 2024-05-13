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
use common\modules\npis\models\StaffCompetencyIndicator;
use common\modules\npis\models\StaffCompetencyIndicatorEvidence;
use common\modules\npis\models\TrainingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
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
                        'actions' => ['view', 'my-current-position', 'my-career-path', 'my-competencies', 'view-competencies', 'view-selected-competency', 'view-indicator', 'view-evidences', 'view-trainings', 'create-evidence', 'update-evidence'],
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

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionMyCurrentPosition()
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

        return $this->renderAjax('my-current-position', [
            'model' => $model,
        ]);
    }

    public function actionViewCompetencies()
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

        $descriptors = PositionCompetencyIndicator::find()
            ->select([
                'competency.comp_id as id',
                'competency.competency as competency',
                'max(proficiency) as proficiency',
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
            ->groupBy(['competency.comp_id'])
            ->orderBy([
                'type' => SORT_ASC,
                'competency' => SORT_ASC,
            ])
            ->asArray()
            ->all();

        $availableDescriptors = [];

        usort($descriptors, function($a, $b) {
            $order = ['Organizational', 'Managerial', 'Technical/Functional'];
            $index_a = array_search($a['type'], $order);
            $index_b = array_search($b['type'], $order);
            return $index_a - $index_b;
        });

        if(!empty($descriptors)){
            foreach($descriptors as $i => $descriptor){
                $item = [];
                $percent = Competency::findOne(['comp_id' => $descriptor['id']])->getStaffCompetencyPercentage();

                if($percent > 0 && $percent < 100){
                    $item['label'] = '<table style="font-size: 14px; width: 100%; height: 100% !important; background:
                    linear-gradient(90deg, rgba(164,212,180,1) '.$percent.'%, #F5F5F5 '.$percent.'%) !important;"><tr><td style="padding: 10px; "onclick="viewSelectedCompetency('.$descriptor['id'].')">'.$descriptor['competency'].' ('.$descriptor['proficiency'].')</td></tr></table>';
                }else if($percent == 100){
                    $item['label'] = '<table style="font-size: 14px; width: 100%; height: 100% !important; background: rgba(164,212,180,1) !important;"><tr><td style="padding: 10px; color: white; "onclick="viewSelectedCompetency('.$descriptor['id'].')">'.$descriptor['competency'].' ('.$descriptor['proficiency'].')</td></tr></table>';
                }else{
                    $item['label'] = '<table style="font-size: 14px; width: 100%; height: 100% !important;"><tr><td style="padding: 10px; "onclick="viewSelectedCompetency('.$descriptor['id'].')">'.$descriptor['competency'].' ('.$descriptor['proficiency'].')</td></tr></table>';
                }
                $item['content'] = '<div id="my-selected-competency-'.$descriptor['id'].'-information"></div>';
                $item['options'] = ['class' => 'panel panel-default'];

                $availableDescriptors[$descriptor['type']][] = $item;
            }
        }


        return $this->renderAjax('competencies', [
            'model' => $model,
            'availableDescriptors' => $availableDescriptors,
        ]);
    }

    public function actionViewSelectedCompetency($competency_id)
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

        $competency = Competency::findOne(['comp_id' => $competency_id]);
        
        $descriptors = $model ? PositionCompetencyIndicator::find()
        ->select([
            'competency_indicator.id as id',
            'competency.competency as competency',
            'competency.description as description',
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
            'position_id' => $model->item_no,
            'competency.comp_id' => $competency->comp_id
        ])
        ->orderBy([
            'type' => SORT_ASC,
            'competency' => SORT_ASC,
        ])
        ->asArray()
        ->all() : [];

        $availableDescriptors = [];
        $descriptorModels = [];
        
        $checkCompetencyProficiencies = [];
        $isChecked = true;

        if($descriptors){
            foreach($descriptors as $descriptor){
                $checkCompetencyProficiencies[$descriptor['proficiency']] = 0;
            }
        }

        if($descriptors){
            foreach($descriptors as $descriptor){
                $availableDescriptors[$descriptor['proficiency']][] = $descriptor;

                $indicatorModel = StaffCompetencyIndicator::findOne(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N, 'position_id' => $model->item_no, 'indicator_id' => $descriptor['id']]) ? StaffCompetencyIndicator::findOne(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N, 'position_id' => $model->item_no, 'indicator_id' => $descriptor['id']]) : new StaffCompetencyIndicator();

                $indicatorModel->emp_id = Yii::$app->user->identity->userinfo->EMP_N;
                $indicatorModel->position_id = $model->item_no;
                $indicatorModel->indicator_id = $descriptor['id'];

                $descriptorModels[$descriptor['proficiency']][$descriptor['id']] = $indicatorModel;

                $checkCompetencyProficiencies[$descriptor['proficiency']] += !$indicatorModel->isNewRecord ? $indicatorModel->compliance : 0; 
            }
        }

        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post();

            $indicatorModel = StaffCompetencyIndicator::findOne(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N, 'position_id' => $model->item_no, 'indicator_id' => $postData['id']]) ? StaffCompetencyIndicator::findOne(['emp_id' => Yii::$app->user->identity->userinfo->EMP_N, 'position_id' => $model->item_no, 'indicator_id' => $postData['id']]) : new StaffCompetencyIndicator();

            $indicatorModel->emp_id = Yii::$app->user->identity->userinfo->EMP_N;
            $indicatorModel->position_id = $model->item_no;
            $indicatorModel->indicator_id = $postData['id'];
            $indicatorModel->compliance = $postData['value'];

            $indicatorModel->save();
        }

        return $this->renderAjax('competency', [
            'model' => $model,
            'competency' => $competency,
            'availableDescriptors' => $availableDescriptors,
            'checkCompetencyProficiencies' => $checkCompetencyProficiencies,
            'descriptorModels' => $descriptorModels,
        ]);
    }

    public function actionViewIndicator($id)
    {
        $indicator = CompetencyIndicator::findOne($id);

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

        $staffIndicatorModel = $model ? StaffCompetencyIndicator::findOne(['emp_id' => $model->emp_id, 'position_id' => $model->item_no, 'indicator_id' => $indicator->id]) ? StaffCompetencyIndicator::findOne(['emp_id' => $model->emp_id, 'position_id' => $model->item_no, 'indicator_id' => $indicator->id]) : new StaffCompetencyIndicator() : new StaffCompetencyIndicator();

        $staffIndicatorModel->emp_id = Yii::$app->user->identity->userinfo->EMP_N;
        $staffIndicatorModel->position_id = $model ? $model->item_no : '';
        $staffIndicatorModel->indicator_id = $indicator->id;

        $staffIndicatorModel->save();

        return $this->renderAjax('indicator', [
            'indicator' => $indicator,
            'model' => $model,
        ]);
    }

    public function actionViewEvidences($id)
    {
        $indicator = CompetencyIndicator::findOne($id);

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
        
        $staffIndicator = StaffCompetencyIndicator::findOne(['emp_id' => $model->emp_id, 'position_id' => $model->item_no, 'indicator_id' => $indicator->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => StaffCompetencyIndicatorEvidence::find()
                ->where(['staff_competency_indicator_id' => $staffIndicator->id])
                ->orderBy([
                    'start_date' => SORT_DESC,
                ]),
        ]);

        $evidences = StaffCompetencyIndicatorEvidence::find()
        ->where(['staff_competency_indicator_id' => $staffIndicator->id])
        ->orderBy([
            'start_date' => SORT_DESC,
        ])
        ->all();

        $evidenceModels = [];

        if(!empty($evidences))
        {
            foreach($evidences as $idx => $evidence)
            {
                $evidenceModels[$idx + 1] = $evidence;
            }
        }


        return $this->renderAjax('evidences', [
            'indicator' => $indicator,
            'model' => $model,
            'dataProvider' => $dataProvider,
            'evidenceModels' => $evidenceModels,
        ]);
    }

    public function actionCreateEvidence($id)
    {
        $indicator = CompetencyIndicator::findOne($id);

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

        $staffIndicator = StaffCompetencyIndicator::findOne(['emp_id' => $model->emp_id, 'position_id' => $model->item_no, 'indicator_id' => $indicator->id]);

        $evidenceModel = new StaffCompetencyIndicatorEvidence();
        $evidenceModel->staff_competency_indicator_id = $staffIndicator->id;

        return $this->renderAjax('_form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
        ]);
    }
}