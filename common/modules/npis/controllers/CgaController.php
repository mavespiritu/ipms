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
use common\modules\npis\models\EmployeeTraining;
use common\modules\npis\models\EmployeeTrainingId;
use common\modules\npis\models\EmployeeOtherInfo;
use common\modules\npis\models\EmployeeOtherInfoId;
use common\modules\npis\models\TrainingDiscipline;
use common\modules\npis\models\TrainingCategory;
use common\modules\npis\models\EvidenceTraining;
use common\modules\npis\models\EvidenceAward;
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
                        'actions' => [
                            'view', 
                            'my-current-position', 
                            'my-career-path', 
                            'my-competencies', 
                            'view-competencies', 
                            'view-selected-competency', 
                            'view-indicator', 
                            'view-evidences', 
                            'view-trainings', 
                            'create-training', 
                            'select-training', 
                            'new-training', 
                            'update-training',
                            'select-award', 
                            'update-award', 
                            'update-performance',
                            'create-others', 
                            'update-others',
                            'delete-evidence'
                        ],
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
                    linear-gradient(90deg, rgba(164,212,180,1) '.$percent.'%, #F5F5F5 '.$percent.'%) !important;"><tr><td style="padding: 10px;" onClick="viewSelectedCompetency('.$descriptor['id'].')">'.$descriptor['competency'].' ('.$descriptor['proficiency'].')</td>
                    <td align=right style="padding: 10px;">'.number_format($percent, 2).'%</td></tr></table>';
                }else if($percent == 100){
                    $item['label'] = '<table style="font-size: 14px; width: 100%; height: 100% !important; background: rgba(164,212,180,1) !important;"><tr><td style="padding: 10px;" onClick="viewSelectedCompetency('.$descriptor['id'].')">'.$descriptor['competency'].' ('.$descriptor['proficiency'].')</td>
                    <td align=right style="padding: 10px;">'.$percent.'%</td></tr></table>';
                }else{
                    $item['label'] = '<table style="font-size: 14px; width: 100%; height: 100% !important;"><tr><td style="padding: 10px;" onClick="viewSelectedCompetency('.$descriptor['id'].')">'.$descriptor['competency'].' ('.$descriptor['proficiency'].')</td></tr></table>';
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
            'staffIndicatorModel' => $staffIndicatorModel,
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

    public function actionCreateTraining($id, $reference)
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

        return $this->renderAjax('_training-form.php', [
            'indicator' => $indicator,
            'reference' => $reference,
            'idx' => 0,
        ]);
    }

    public function actionSelectTraining($id, $reference)
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

        $trainings = EmployeeTraining::find()
            ->select([
                'concat(seminar_title,"__",from_date) as id',
                'IF(from_date <> to_date, concat(seminar_title," (",from_date," to ",to_date,")"), concat(seminar_title," (",from_date,")")) as title'
            ])
            ->where([
                'emp_id' => $model->emp_id,
                'approval' => 'yes'
            ])
            ->orderBy(['seminar_title' => SORT_ASC])
            ->asArray()
            ->all();

        $trainings = ArrayHelper::map($trainings, 'id', 'title');

        $evidenceModel = new StaffCompetencyIndicatorEvidence();
        $evidenceModel->staff_competency_indicator_id = $staffIndicator->id;
        $evidenceModel->reference = $reference;

        $evidenceTrainingModel = new EvidenceTraining();

        if($evidenceTrainingModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            $trainingID = explode("__", $evidenceTrainingModel->seminar_title);

            $selectedTraining = EmployeeTraining::findOne([
                'emp_id' => $model->emp_id,
                'seminar_title' => $trainingID[0],
                'from_date' => $trainingID[1],
                'approval' => 'yes'
            ]);

            $evidenceModel->description = "Attendance to ".$selectedTraining->seminar_title;
            $evidenceModel->start_date = $selectedTraining->from_date;
            $evidenceModel->end_date = $selectedTraining->to_date;
            try {
                if($evidenceModel->save()){
                    $evidenceTrainingModel->evidence_id = $evidenceModel->id;
                    $evidenceTrainingModel->emp_id = $model->emp_id;
                    $evidenceTrainingModel->seminar_title = $selectedTraining->seminar_title;
                    $evidenceTrainingModel->from_date = $selectedTraining->from_date;
                    $evidenceTrainingModel->save();

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been saved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving evidence');
            }
        }

        return $this->renderAjax('_training-select-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'evidenceTrainingModel' => $evidenceTrainingModel,
            'trainings' => $trainings,
            'reference' => $reference,
            'idx' => 0,
            'action' => 'create'
        ]);
    }

    public function actionNewTraining($id, $reference)
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
        $evidenceModel->reference = $reference;

        $evidenceTrainingModel = new EvidenceTraining();

        $trainingModel = new EmployeeTraining();
        $trainingModel->emp_id = $model->emp_id;
        $trainingModel->approval = 'no';

        $idModel = new EmployeeTrainingId();
        $idModel->emp_id = $model->emp_id;

        $disciplines = TrainingDiscipline::find()->all();
        $disciplines = ArrayHelper::map($disciplines, 'discipline', 'discipline');

        $categories = TrainingCategory::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        if($trainingModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($trainingModel->save())
                {
                    $evidenceModel->description = "Attendance to ".$trainingModel->seminar_title;
                    $evidenceModel->start_date = $trainingModel->from_date;
                    $evidenceModel->end_date = $trainingModel->to_date;

                    if($evidenceModel->save()){
                        $idModel->seminar_title = $trainingModel->seminar_title;
                        $idModel->from_date = $trainingModel->from_date;
                        $idModel->save();
    
                        $evidenceTrainingModel->evidence_id = $evidenceModel->id;
                        $evidenceTrainingModel->emp_id = $model->emp_id;
                        $evidenceTrainingModel->seminar_title = $trainingModel->seminar_title;
                        $evidenceTrainingModel->from_date = $trainingModel->from_date;
                        $evidenceTrainingModel->save();
    
                        $transaction->commit();
                        \Yii::$app->getSession()->setFlash('success', 'Evidence has been saved successfully');
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving evidence');
            }
        }

        return $this->renderAjax('_training-new-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'trainingModel' => $trainingModel,
            'idModel' => $idModel,
            'evidenceTrainingModel' => $evidenceTrainingModel,
            'disciplines' => $disciplines,
            'categories' => $categories,
            'reference' => $reference,
            'idx' => 0,
            'action' => 'create'
        ]);
    }

    public function actionSelectAward($id, $reference)
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

        $awards = EmployeeOtherInfo::find()
            ->select([
                'description'
            ])
            ->where([
                'emp_id' => $model->emp_id,
                'type' => 'recognition',
                'approval' => 'yes'
            ])
            ->orderBy(['description' => SORT_ASC])
            ->asArray()
            ->all();

        $awards = ArrayHelper::map($awards, 'description', 'description');

        $evidenceModel = new StaffCompetencyIndicatorEvidence();
        $evidenceModel->staff_competency_indicator_id = $staffIndicator->id;
        $evidenceModel->reference = $reference;

        $evidenceAwardModel = new EvidenceAward();

        if($evidenceAwardModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            $selectedAward = EmployeeOtherInfo::findOne([
                'emp_id' => $model->emp_id,
                'type' => 'recognition',
                'description' => $evidenceAwardModel->description,
                'approval' => 'yes'
            ]);

            $evidenceModel->description = "Awarded with ".$selectedAward->description;
            $evidenceModel->start_date = $selectedAward->year.'-01-01';
            $evidenceModel->end_date = $selectedAward->year.'-01-01';
            try {
                if($evidenceModel->save()){
                    $evidenceAwardModel->evidence_id = $evidenceModel->id;
                    $evidenceAwardModel->emp_id = $model->emp_id;
                    $evidenceAwardModel->type = 'recognition';
                    $evidenceAwardModel->description = $selectedAward->description;
                    $evidenceAwardModel->save();

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been saved successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving evidence');
            }
        }

        return $this->renderAjax('_award-select-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'evidenceAwardModel' => $evidenceAwardModel,
            'awards' => $awards,
            'reference' => $reference,
            'action' => 'create',
            'idx' => 0,
        ]);
    }

    public function actionCreatePerformance($id, $reference)
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
        $evidenceModel->reference = $reference;

        if($evidenceModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($evidenceModel->save()){
    
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been saved successfully');
                } 
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving evidence');
            }
        }

        return $this->renderAjax('_performance-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'reference' => $reference,
            'idx' => 0,
            'action' => 'create'
        ]);
    }

    public function actionCreateOthers($id, $reference)
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
        $evidenceModel->reference = $reference;

        if($evidenceModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($evidenceModel->save()){
    
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been saved successfully');
                } 
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while saving evidence');
            }
        }

        return $this->renderAjax('_others-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'reference' => $reference,
            'idx' => 0,
            'action' => 'create'
        ]);
    }

    public function actionUpdateTraining($id)
    {
        $evidenceModel = StaffCompetencyIndicatorEvidence::findOne(['id' => $id]);

        $indicator = CompetencyIndicator::findOne($evidenceModel->staffCompetencyIndicator->indicator_id);

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

        $trainings = EmployeeTraining::find()
            ->select([
                'concat(seminar_title,"__",from_date) as id',
                'IF(from_date <> to_date, concat(seminar_title," (",from_date," to ",to_date,")"), concat(seminar_title," (",from_date,")")) as title'
            ])
            ->where([
                'emp_id' => $model->emp_id,
                'approval' => 'yes'
            ])
            ->orderBy(['seminar_title' => SORT_ASC])
            ->asArray()
            ->all();

        $trainings = ArrayHelper::map($trainings, 'id', 'title');

        $evidenceTrainingModel = $evidenceModel->evidenceTraining;

        $trainingModel = EmployeeTraining::findOne([
            'emp_id' => $model->emp_id,
            'seminar_title' => $evidenceTrainingModel->seminar_title,
            'from_date' => $evidenceTrainingModel->from_date,
        ]);

        $idModel = $trainingModel->id ? $trainingModel->id : new EmployeeTrainingId();
        $idModel->emp_id = $model->emp_id;

        $evidenceTrainingModel->seminar_title = $evidenceTrainingModel->seminar_title.'__'.$evidenceTrainingModel->from_date;

        $disciplines = TrainingDiscipline::find()->all();
        $disciplines = ArrayHelper::map($disciplines, 'discipline', 'discipline');

        $categories = TrainingCategory::find()->all();
        $categories = ArrayHelper::map($categories, 'category', 'category');

        if(
            $evidenceTrainingModel->load(Yii::$app->request->post()) ||
            $trainingModel->load(Yii::$app->request->post())
        ){

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($trainingModel->approval == 'yes'){
                    $trainingID = explode("__", $evidenceTrainingModel->seminar_title);

                    $selectedTraining = EmployeeTraining::findOne([
                        'emp_id' => $model->emp_id,
                        'seminar_title' => $trainingID[0],
                        'from_date' => $trainingID[1],
                        'approval' => 'yes'
                    ]);

                    $evidenceModel->description = "Attendance to ".$selectedTraining->seminar_title;
                    $evidenceModel->start_date = $selectedTraining->from_date;
                    $evidenceModel->end_date = $selectedTraining->to_date;

                    if($evidenceModel->save()){
                        $evidenceTrainingModel->evidence_id = $evidenceModel->id;
                        $evidenceTrainingModel->emp_id = $model->emp_id;
                        $evidenceTrainingModel->seminar_title = $selectedTraining->seminar_title;
                        $evidenceTrainingModel->from_date = $selectedTraining->from_date;
                        $evidenceTrainingModel->save();
    
                        $transaction->commit();
                        \Yii::$app->getSession()->setFlash('success', 'Evidence has been updated successfully');
                    }else{
                        $transaction->rollBack();
                        \Yii::$app->getSession()->setFlash('error', 'Error occurred while updating evidence');
                    }

                }else{
                    if($trainingModel->save())
                    {
                        $evidenceModel->description = "Attendance to ".$trainingModel->seminar_title;
                        $evidenceModel->start_date = $trainingModel->from_date;
                        $evidenceModel->end_date = $trainingModel->to_date;

                        if($evidenceModel->save()){
                            $idModel->seminar_title = $trainingModel->seminar_title;
                            $idModel->from_date = $trainingModel->from_date;
                            $idModel->save();
        
                            $evidenceTrainingModel->evidence_id = $evidenceModel->id;
                            $evidenceTrainingModel->emp_id = $model->emp_id;
                            $evidenceTrainingModel->seminar_title = $trainingModel->seminar_title;
                            $evidenceTrainingModel->from_date = $trainingModel->from_date;
                            $evidenceTrainingModel->save();
        
                            $transaction->commit();
                            \Yii::$app->getSession()->setFlash('success', 'Evidence has been saved successfully');
                        }
                    }else{
                        $transaction->rollBack();
                        \Yii::$app->getSession()->setFlash('error', 'Error occurred while updating evidence');
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while updating evidence');
            }
        }


        if($trainingModel->approval == 'yes'){
            return $this->renderAjax('_training-select-form.php', [
                'indicator' => $indicator,
                'model' => $model,
                'staffIndicator' => $staffIndicator,
                'evidenceModel' => $evidenceModel,
                'evidenceTrainingModel' => $evidenceTrainingModel,
                'trainings' => $trainings,
                'reference' => $evidenceModel->reference,
                'idx' => $evidenceModel->id,
                'action' => 'update'
            ]);
        }else{
            return $this->renderAjax('_training-new-form.php', [
                'indicator' => $indicator,
                'model' => $model,
                'staffIndicator' => $staffIndicator,
                'evidenceModel' => $evidenceModel,
                'trainingModel' => $trainingModel,
                'idModel' => $idModel,
                'evidenceTrainingModel' => $evidenceTrainingModel,
                'disciplines' => $disciplines,
                'categories' => $categories,
                'reference' => $evidenceModel->reference,
                'idx' => $evidenceModel->id,
                'action' => 'update'
            ]);
        }
    }

    public function actionUpdateAward($id)
    {
        $evidenceModel = StaffCompetencyIndicatorEvidence::findOne(['id' => $id]);

        $indicator = CompetencyIndicator::findOne($evidenceModel->staffCompetencyIndicator->indicator_id);

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

        $awards = EmployeeOtherInfo::find()
            ->select([
                'description'
            ])
            ->where([
                'emp_id' => $model->emp_id,
                'type' => 'recognition',
                'approval' => 'yes'
            ])
            ->orderBy(['description' => SORT_ASC])
            ->asArray()
            ->all();

        $awards = ArrayHelper::map($awards, 'description', 'description');

        $evidenceAwardModel = $evidenceModel->evidenceAward;

        if($evidenceAwardModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            $selectedAward = EmployeeOtherInfo::findOne([
                'emp_id' => $model->emp_id,
                'type' => 'recognition',
                'description' => $evidenceAwardModel->description,
                'approval' => 'yes'
            ]);

            $evidenceModel->description = "Awarded with ".$selectedAward->description;
            $evidenceModel->start_date = $selectedAward->year.'-01-01';
            $evidenceModel->end_date = $selectedAward->year.'-01-01';
            try {
                if($evidenceModel->save()){
                    $evidenceAwardModel->evidence_id = $evidenceModel->id;
                    $evidenceAwardModel->emp_id = $model->emp_id;
                    $evidenceAwardModel->type = 'recognition';
                    $evidenceAwardModel->description = $selectedAward->description;
                    $evidenceAwardModel->save();

                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been updated successfully');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while updating evidence');
            }
        }

        return $this->renderAjax('_award-select-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'evidenceAwardModel' => $evidenceAwardModel,
            'awards' => $awards,
            'reference' => $evidenceModel->reference,
            'action' => 'update',
            'idx' => $evidenceModel->id,
        ]);
    }
    
    public function actionUpdatePerformance($id)
    {
        $evidenceModel = StaffCompetencyIndicatorEvidence::findOne(['id' => $id]);

        $indicator = CompetencyIndicator::findOne($evidenceModel->staffCompetencyIndicator->indicator_id);

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

        if($evidenceModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($evidenceModel->save()){
    
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been updated successfully');
                } 
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while updating evidence');
            }
        }

        return $this->renderAjax('_others-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'reference' => $evidenceModel->reference,
            'idx' => $evidenceModel->id,
            'action' => 'update'
        ]);
    }

    public function actionUpdateOthers($id)
    {
        $evidenceModel = StaffCompetencyIndicatorEvidence::findOne(['id' => $id]);

        $indicator = CompetencyIndicator::findOne($evidenceModel->staffCompetencyIndicator->indicator_id);

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

        if($evidenceModel->load(Yii::$app->request->post())){

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($evidenceModel->save()){
    
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been updated successfully');
                } 
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while updating evidence');
            }
        }

        return $this->renderAjax('_others-form.php', [
            'indicator' => $indicator,
            'model' => $model,
            'staffIndicator' => $staffIndicator,
            'evidenceModel' => $evidenceModel,
            'reference' => $evidenceModel->reference,
            'idx' => $evidenceModel->id,
            'action' => 'update'
        ]);
    }

    public function actionDeleteEvidence($id)
    {
        if(Yii::$app->request->post()){

            $evidence = StaffCompetencyIndicatorEvidence::findOne(['id' => $id]);

            $transaction = Yii::$app->ipms->beginTransaction();

            try {
                if($evidence->delete()){
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', 'Evidence has been deleted successfully');
                }else{
                    $transaction->rollBack();
                    \Yii::$app->getSession()->setFlash('error', 'Error occurred while deleting evidence');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('error', 'Error occurred while deleting evidence');
            }
        }
    }
}