<?php

namespace common\modules\npis\controllers;

use Yii;
use common\modules\npis\models\CareerPath;
use common\modules\npis\models\EmployeeItem;
use common\modules\npis\models\EmployeePositionItem;
use common\modules\npis\models\DesignationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * DesignationController implements the CRUD actions for CareerPath model.
 */
class DesignationController extends Controller
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
                        'actions' => ['index', 'position-list'],
                        'allow' => true,
                        'roles' => ['designation-index'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['designation-create'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['designation-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['designation-delete'],
                    ],
                ],
            ],
        ];
    }

    public function actionPositionList($id)
    {
        $model = EmployeeItem::find()
            ->andWhere([
                'emp_id' => $id
            ])
            ->andWhere([
                'is', 'to_date', null
            ])
            ->orderBy([
                'from_date' => SORT_DESC
            ])
            ->one();

        $currentPosition = EmployeePositionItem::findOne(['item_no' => $model->item_no]);

        $positions = EmployeePositionItem::find()
            ->select(['item_no', 'concat(division_id,": ",p.post_description," (",item_no,")") as title'])
            ->leftJoin('tblposition p', 'p.position_id = tblemp_position_item.position_id')
            ->andWhere(['<>', 'item_no', $currentPosition->item_no])
            ->andWhere(['status' => 1])
            ->orderBy([
                'tblemp_position_item.division_id' => SORT_ASC,
                'p.post_description' => SORT_ASC,
            ])
            ->groupBy(['item_no'])
            ->asArray()
            ->all();

        $arr = [];
        $arr[] = ['id'=>'','text'=>''];
        foreach($positions as $position){
            $arr[] = ['id' => $position['item_no'] ,'text' => $position['title']];
        }
        \Yii::$app->response->format = 'json';
        return $arr;
    }

    /**
     * Lists all CareerPath models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DesignationSearch();
        $searchModel->type = 'Designation';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $designationModels = [];

        $designations = CareerPath::find()->where(['type' => 'Designation'])->all();

        if($designations){
            foreach($designations as $designation){
                $designationModels[$designation->id] = $designation;
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
            'designationModels' => $designationModels,
        ]);
    }

    /**
     * Displays a single CareerPath model.
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
     * Creates a new CareerPath model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CareerPath();
        $model->type = 'Designation';
        $model->scenario = 'designationForm';

        $positions = [];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Record Saved');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'positions' => $positions,
        ]);
    }

    /**
     * Updates an existing CareerPath model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'designationForm';

        $staff = EmployeeItem::find()
        ->andWhere([
            'emp_id' => $model->emp_id
        ])
        ->andWhere([
            'is', 'to_date', null
        ])
        ->orderBy([
            'from_date' => SORT_DESC
        ])
        ->one();

        $currentPosition = EmployeePositionItem::findOne(['item_no' => $staff->item_no]);

        $positions = EmployeePositionItem::find()
            ->select(['item_no', 'concat(division_id,": ",p.post_description," (",item_no,")") as title'])
            ->leftJoin('tblposition p', 'p.position_id = tblemp_position_item.position_id')
            ->andWhere(['<>', 'item_no', $currentPosition->item_no])
            ->andWhere(['status' => 1])
            ->orderBy([
                'tblemp_position_item.division_id' => SORT_ASC,
                'p.post_description' => SORT_ASC,
            ])
            ->groupBy(['item_no'])
            ->asArray()
            ->all();

        $positions = ArrayHelper::map($positions, 'item_no', 'title');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', 'Record Updated');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'positions' => $positions,
        ]);
    }

    /**
     * Deletes an existing CareerPath model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        \Yii::$app->getSession()->setFlash('success', 'Record Deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the CareerPath model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CareerPath the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CareerPath::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
