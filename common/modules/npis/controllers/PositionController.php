<?php

namespace common\modules\npis\controllers;

use Yii;
use common\models\Division;
use common\models\Position;
use common\modules\npis\models\Competency;
use common\modules\npis\models\CompetencyIndicator;
use common\modules\npis\models\PositionCompetencyIndicator;
use common\modules\npis\models\EmployeePositionItem;
use common\modules\npis\models\EmployeePositionItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Positionontroller implements the CRUD actions for Position model.
 */
class PositionController extends Controller
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
                        'roles' => ['position-item-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['position-item-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['position-item-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['position-item-delete'],
                    ],
                    [
                        'actions' => ['view', 'select-competency', 'view-competency', 'view-selected-competency'],
                        'allow' => true,
                        'roles' => ['position-item-view'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Position models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeePositionItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $divisions = Division::find()->where(['is not', 'item_no', null])->all();
        $divisions = ArrayHelper::map($divisions, 'division_id', 'division_id');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'divisions' => $divisions,
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
        $competencies = Competency::find()
            ->select([
                'comp_id',
                'competency',
                'description',
                'type' => new \yii\db\Expression('CASE 
                    WHEN comp_type = "org" THEN "Organizational"
                    WHEN comp_type = "mnt" THEN "Managerial"
                    ELSE "Technical/Functional"
                END')
            ])
            ->asArray()
            ->orderBy(['competency' => SORT_ASC])
            ->all();

        usort($competencies, function($a, $b) {
            $order = ['Organizational', 'Managerial', 'Technical/Functional'];
            $index_a = array_search($a['type'], $order);
            $index_b = array_search($b['type'], $order);
            return $index_a - $index_b;
        });

        $competencies = ArrayHelper::map($competencies, 'comp_id', 'competency', 'type');

        return $this->render('view', [
            'model' => $this->findModel($id),
            'competencies' => $competencies,
        ]);
    }

    public function actionSelectCompetency($id, $position_id)
    {
        $model = Competency::findOne($id);
        $position = $this->findModel($position_id);

        $selectedDescriptors = PositionCompetencyIndicator::find()->where(['position_id' => $position_id])->asArray()->all();
        $selectedDescriptors = ArrayHelper::map($selectedDescriptors, 'indicator_id', 'indicator_id');

        $descriptors = CompetencyIndicator::find()
            ->andWhere(['competency_id' => $model->comp_id])
            ->andWhere(['not in', 'id', $selectedDescriptors])
            ->orderBy(['proficiency' => SORT_DESC, 'indicator' => SORT_ASC])
            ->all();

        $availableDescriptors = [];
        $descriptorModels = [];

        if($descriptors){
            foreach($descriptors as $descriptor){
                $descriptorModels[$descriptor->id] = $descriptor;
            }
        }

        if($descriptors){
            foreach($descriptors as $descriptor){
                $availableDescriptors[$descriptor->proficiency][] = $descriptor;
            }
        }

        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post('CompetencyIndicator');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);

            $transaction = Yii::$app->db->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(!empty($selectedIndexes)){
                    foreach($selectedIndexes as $index){
                        $descriptorModel = PositionCompetencyIndicator::findOne([
                            'position_id' => $position->item_no,
                            'indicator_id' => $index
                        ]) ? PositionCompetencyIndicator::findOne([
                            'position_id' => $position->item_no,
                            'indicator_id' => $index
                        ]) : new PositionCompetencyIndicator();

                        $descriptorModel->position_id = $position->item_no;
                        $descriptorModel->indicator_id = $index;
                        $descriptorModel->save();
                    }
                    
                    $transaction->commit();
                    return [
                        'success' => 'Records have been saved successfully',
                        'indexes' => $selectedIndexes
                    ];
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while adding records'];
            }
        }
        
        return $this->renderAjax('competency-list', [
            'model' => $model,
            'position' => $position,
            'descriptorModels' => $descriptorModels,
            'availableDescriptors' => $availableDescriptors,
        ]);
    }
    
    public function actionViewCompetency($id)
    {
        $model = $this->findModel($id);

        $descriptors = PositionCompetencyIndicator::find()
        ->select([
            'competency.comp_id as id',
            'competency.competency as competency',
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

                $item['label'] = '<span style="font-size: 14px;" onclick="viewSelectedCompetency(\''.$model->item_no.'\', '.$descriptor['id'].')">'.$descriptor['competency'].'</span>';
                $item['content'] = '<div id="selected-competency-'.$descriptor['id'].'-information"></div>';
                $item['options'] = ['class' => 'panel panel-default'];

                $availableDescriptors[$descriptor['type']][] = $item;
            }
        }

        return $this->renderAjax('competency', [
            'model' => $model,
            'availableDescriptors' => $availableDescriptors,
        ]);
    }

    public function actionViewSelectedCompetency($position_id, $competency_id)
    {
        $model = $this->findModel($position_id);
        $competency = Competency::findOne(['comp_id' => $competency_id]);
        
        $descriptors = PositionCompetencyIndicator::find()
        ->select([
            'competency_indicator.id as id',
            'competency_indicator.indicator as indicator',
            'competency_indicator.proficiency as proficiency',
        ])
        ->leftJoin('competency_indicator', 'competency_indicator.id = position_competency_indicator.indicator_id')
        ->where([
            'position_id' => $model->item_no,
            'competency_indicator.competency_id' => $competency->comp_id,
        ])
        ->orderBy([
            'proficiency' => SORT_DESC,
            'indicator' => SORT_ASC,
        ])
        ->asArray()
        ->all();

        $availableDescriptors = [];
        $descriptorModels = [];

        if($descriptors){
            foreach($descriptors as $descriptor){
                $descriptorModels[$descriptor['id']] = PositionCompetencyIndicator::findOne(['position_id' => $model->item_no, 'indicator_id' => $descriptor['id']]);
            }
        }

        if($descriptors){
            foreach($descriptors as $descriptor){
                $availableDescriptors[$descriptor['proficiency']][] = $descriptor;
            }
        }

        if(Yii::$app->request->post()){
            $postData = Yii::$app->request->post('PositionCompetencyIndicator');
            $selectedIndexes = ArrayHelper::map($postData, 'id', 'id');
            $selectedIndexes = array_filter($selectedIndexes, function($value) {
                return $value != 0;
            });
            $selectedIndexes = array_values($selectedIndexes);

            $transaction = Yii::$app->db->beginTransaction();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            try {
                if(PositionCompetencyIndicator::deleteAll([
                    'position_id' => $model->item_no,
                    'indicator_id' => $selectedIndexes
                ])){
                    $transaction->commit();
                    return [
                        'success' => 'Records have been removed successfully',
                        'indexes' => $selectedIndexes
                    ];
                }

            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['error' => 'Error occurred while removing records'];
            }
        }

        return $this->renderAjax('indicator', [
            'model' => $model,
            'competency' => $competency,
            'descriptorModels' => $descriptorModels,
            'availableDescriptors' => $availableDescriptors,
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
        if (($model = EmployeePositionItem::findOne(['item_no' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
