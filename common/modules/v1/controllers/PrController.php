<?php

namespace common\modules\v1\controllers;

use Yii;
use common\modules\v1\models\Pr;
use common\modules\v1\models\Apr;
use common\modules\v1\models\Rfq;
use common\modules\v1\models\RfqInfo;
use common\modules\v1\models\AprItem;
use common\modules\v1\models\PrSearch;
use common\modules\v1\models\PrItem;
use common\modules\v1\models\PrItemSpec;
use common\modules\v1\models\PrItemCost;
use common\modules\v1\models\Supplier;
use common\modules\v1\models\PrItemSpecValue;
use common\modules\v1\models\PrItemSearch;
use common\modules\v1\models\AppropriationItem;
use common\modules\v1\models\Activity;
use common\modules\v1\models\SubActivity;
use common\modules\v1\models\FUndSource;
use common\modules\v1\models\Ris;
use common\modules\v1\models\Month;
use common\modules\v1\models\Ppmp;
use common\modules\v1\models\Obj;
use common\modules\v1\models\Item;
use common\modules\v1\models\PpmpItem;
use common\modules\v1\models\ItemCost;
use common\modules\v1\models\ItemBreakdown;
use common\modules\v1\models\PpmpItemSearch;
use common\modules\v1\models\FundCluster;
use common\modules\v1\models\ProcurementMode;
use common\modules\v1\models\Signatory;
use common\modules\v1\models\RisItem;
use common\modules\v1\models\RisItemSpec;
use common\modules\v1\models\RisItemSpecValue;
use common\modules\v1\models\RisSource;
use common\modules\v1\models\RisSearch;
use common\modules\v1\models\ForContractItem;
use common\modules\v1\models\Settings;
use common\modules\v1\models\Model;
use common\modules\v1\models\MultipleModel;
use common\modules\v1\models\Transaction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use markavespiritu\user\models\Office;
use yii\widgets\ActiveForm;
use yii\web\Response;
use kartik\mpdf\Pdf;

/**
 * PrController implements the CRUD actions for Pr model.
 */
class PrController extends Controller
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
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['ProcurementStaff', 'Administrator'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Pr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $types = [
            'Supply' => 'Goods',
            'Service' => 'Service/Contract',
        ];

        $fundSources = FundSource::find()->all();
        $fundSources = ArrayHelper::map($fundSources, 'id', 'code');

        $offices = Office::find()->all();
        $offices = ArrayHelper::map($offices, 'abbreviation', 'abbreviation');

        $procurementModes = ProcurementMode::find()->all();
        $procurementModes = ArrayHelper::map($procurementModes, 'id', 'title');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'types' => $types,
            'fundSources' => $fundSources,
            'offices' => $offices,
            'procurementModes' => $procurementModes,
        ]);
    }

    /**
     * Displays a single Pr model.
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

    public function actionHome($id)
    {
        return $this->renderAjax('_home', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionItems($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'selectRis';

        $statusIDs = Transaction::find()->select(['max(id) as id'])->where(['model' => 'Ris'])->groupBy(['model_id'])->asArray()->all();
        $statusIDs = ArrayHelper::map($statusIDs, 'id', 'id');
        $status = Transaction::find()->where(['in', 'id', $statusIDs])->createCommand()->getRawSql();

        $rises = Ris::find()
                ->select([
                    'ppmp_ris.id as id',
                    'concat("RIS No. ",ppmp_ris.ris_no," (",ppmp_ris.purpose,")") as title',
                    'tbloffice.abbreviation as groupTitle'
                ])
                ->leftJoin(['status' => '('.$status.')'], 'status.model_id = ppmp_ris.id')
                ->leftJoin('tbloffice', 'tbloffice.abbreviation = ppmp_ris.office_id')
                ->andWhere(['status.status' => 'Approved'])
                ->andWhere(['SUBSTRING(ppmp_ris.ris_no, 1, 2)' => substr($model->pr_no, 0, 2)])
                ->andWhere(['ppmp_ris.fund_source_id' => $model->fund_source_id])
                ->asArray()
                ->all();
        
        $rises = ArrayHelper::map($rises, 'id', 'title', 'groupTitle');

        return $this->renderAjax('_items', [
            'model' => $model,
            'rises' => $rises
        ]);
    }

    public function actionLoadRisItems($id, $ris_id)
    {
        $model = $this->findModel($id);

        $existingItems = PrItem::find()->asArray()->all();
        $existingItems = ArrayHelper::map($existingItems, 'ris_item_id', 'ris_item_id');

        $ris = Ris::findOne($ris_id);
        
        $specifications = [];

        $items = RisItem::find()
                ->select([
                    'ppmp_ris_item.id as id',
                    'ppmp_ris_item.ris_id as ris_id',
                    'ppmp_item.id as stockNo',
                    'concat(
                        ppmp_cost_structure.code,"",
                        ppmp_organizational_outcome.code,"",
                        ppmp_program.code,"",
                        ppmp_sub_program.code,"",
                        ppmp_identifier.code,"",
                        ppmp_pap.code,"000-",
                        ppmp_activity.code," - ",
                        ppmp_activity.title
                    ) as prexc',
                    'ppmp_activity.id as activityId',
                    'ppmp_activity.title as activityTitle',
                    'ppmp_sub_activity.id as subActivityId',
                    'ppmp_sub_activity.title as subActivityTitle',
                    'ppmp_item.title as itemTitle',
                    'ppmp_item.unit_of_measure as unitOfMeasure',
                    'ppmp_ppmp_item.cost as cost',
                    'sum(quantity) as total',
                    'ppmp_ris_item.type'
                ])
                ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_ris_item.ppmp_item_id')
                ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                ->leftJoin('ppmp_activity', 'ppmp_activity.id = ppmp_ppmp_item.activity_id')
                ->leftJoin('ppmp_sub_activity', 'ppmp_sub_activity.id = ppmp_ppmp_item.sub_activity_id')
                ->leftJoin('ppmp_pap', 'ppmp_pap.id = ppmp_activity.pap_id')
                ->leftJoin('ppmp_identifier', 'ppmp_identifier.id = ppmp_pap.identifier_id')
                ->leftJoin('ppmp_sub_program', 'ppmp_sub_program.id = ppmp_pap.sub_program_id')
                ->leftJoin('ppmp_program', 'ppmp_program.id = ppmp_pap.program_id')
                ->leftJoin('ppmp_organizational_outcome', 'ppmp_organizational_outcome.id = ppmp_pap.organizational_outcome_id')
                ->leftJoin('ppmp_cost_structure', 'ppmp_cost_structure.id = ppmp_pap.cost_structure_id')
                ->andWhere([
                    'ris_id' => $ris->id,
                ])
                ->andWhere(['in', 'ppmp_ris_item.type', ['Original', 'Supplemental']])
                ->andWhere(['not in', 'ppmp_ris_item.id', $existingItems])
                ->groupBy(['ppmp_item.id', 'ppmp_activity.id', 'ppmp_ris_item.cost'])
                ->asArray()
                ->all();

        $risItems = [];
        $prItems = [];

        if(!empty($items))
        {
            foreach($items as $item)
            {
                $risItems[$item['prexc']][] = $item;
                $prItem = new PrItem();
                $prItem->pr_id = $model->id;
                $prItem->ris_id = $ris->id;
                $prItem->item_id = $item['stockNo'];
                $prItem->activity_id = $item['activityId'];
                $prItem->cost = $item['cost'];
                $prItem->type = $item['type'];
                
                $prItems[$item['id']] = $prItem;

                $spec = RisItemSpec::findOne([
                    'ris_id' => $item['ris_id'],
                    'activity_id' => $item['activityId'],
                    'item_id' => $item['stockNo'],
                    'cost' => $item['cost'],
                    'type' => $item['type'],
                ]);

                if($spec)
                {
                    $specifications[$item['id']] = $spec;
                }
            }
        }

        if(MultipleModel::loadMultiple($prItems, Yii::$app->request->post()))
        {
            $risExistingItems = Yii::$app->request->post('PrItem');

            if(!empty($risExistingItems))
            {
                foreach($risExistingItems as $prItem)
                {
                    if($prItem['ris_item_id'] != 0)
                    {
                        $risItem = RisItem::findOne($prItem['ris_item_id']);

                        $includedItems = RisItem::find()
                            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_ris_item.ppmp_item_id')
                            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                            ->leftJoin('ppmp_activity', 'ppmp_activity.id = ppmp_ppmp_item.activity_id')
                            ->leftJoin('ppmp_sub_activity', 'ppmp_sub_activity.id = ppmp_ppmp_item.sub_activity_id')
                            ->where([
                                'ppmp_ris_item.ris_id' => $risItem->ris_id,
                                'ppmp_activity.id' => $risItem->ppmpItem->activity_id,
                                'ppmp_item.id' => $risItem->ppmpItem->item_id,
                                'ppmp_ris_item.cost' => $risItem->cost,
                                'ppmp_ris_item.type' => $risItem->type,
                            ])
                            ->all();

                        if($includedItems)
                        {
                            foreach($includedItems as $item)
                            {
                                $prItemModel = new PrItem();
                                $prItemModel->pr_id = $model->id;
                                $prItemModel->ris_id = $ris->id;
                                $prItemModel->ris_item_id = $item->id;
                                $prItemModel->ppmp_item_id = $item->ppmp_item_id;
                                $prItemModel->month_id = $item->month_id;
                                $prItemModel->cost = $item->cost;
                                $prItemModel->quantity = $item->quantity;
                                $prItemModel->type = $item->type;
                                $prItemModel->save();
                            }
                        }
                    }
                }
            }
        }

        return $this->renderAjax('_items-ris_items', [
            'model' => $model,
            'risItems' => $risItems,
            'prItems' => $prItems,
            'specifications' => $specifications,
            'ris' => $ris
        ]);
    }

    public function actionPrItems($id)
    {
        $model = $this->findModel($id);
        $prItems = [];
        $specifications = [];
        $items = PrItem::find()
                ->select([
                    'ppmp_pr_item.id as id',
                    's.id as ris_item_spec_id',
                    'ppmp_item.id as item_id',
                    'ppmp_item.title as item',
                    'ppmp_item.unit_of_measure as unit',
                    'ppmp_pr_item.cost as cost',
                    'sum(ppmp_pr_item.quantity) as total',
                ])
                ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                    s.activity_id = ppmp_ppmp_item.activity_id and 
                                                    s.item_id = ppmp_ppmp_item.item_id and 
                                                    s.cost = ppmp_pr_item.cost and 
                                                    s.type = ppmp_pr_item.type')
                ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                ->andWhere([
                    'ppmp_pr_item.pr_id' => $model->id,
                ])
                ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
                ->asArray()
                ->all();

        if(!empty($items))
        {
            foreach($items as $item)
            {
                $prItem = PrItem::findOne($item['id']);
                $prItems[$item['id']] = $prItem;

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        if(MultipleModel::loadMultiple($prItems, Yii::$app->request->post()))
        {
            $prExistingItems = Yii::$app->request->post('PrItem');

            $ids = [];
             if(!empty($prExistingItems))
            {
                foreach($prExistingItems as $prItem)
                {
                    if($prItem['id'] != 0)
                    {
                        $item = PrItem::findOne($prItem['id']);
  
                        $includedItems = PrItem::find()
                            ->select(['ppmp_pr_item.id as id'])
                            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                            ->andWhere([
                                'ppmp_pr_item.pr_id' => $item->pr_id,
                                'ppmp_item.id' => $item->ppmpItem->item_id,
                                'ppmp_pr_item.cost' => $item->cost,
                            ])
                            ->all();
                        
                        $includedItems = ArrayHelper::map($includedItems, 'id', 'id');

                        PrItem::deleteAll(['in', 'id', $includedItems]);
                    }
                }
            }
        }

        return $this->renderAjax('_items-pr_items', [
            'model' => $model,
            'items' => $items,
            'prItems' => $prItems,
            'specifications' => $specifications,
        ]);
    }

    public function actionPr($id)
    {
        $model = $this->findModel($id);
        $prItems = [];
        $specifications = [];
        $entityName = Settings::findOne(['title' => 'Entity Name']);
        $fundCluster = FundCluster::findOne($model->fund_cluster_id);
        $rccs = Pritem::find()
                ->select(['concat(
                    ppmp_cost_structure.code,"",
                    ppmp_organizational_outcome.code,"",
                    ppmp_program.code,"",
                    ppmp_sub_program.code,"",
                    ppmp_identifier.code,"",
                    ppmp_pap.code,"000-",
                    ppmp_activity.code
                ) as prexc',])
                ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                ->leftJoin('ppmp_activity', 'ppmp_activity.id = ppmp_ppmp_item.activity_id')
                ->leftJoin('ppmp_pap', 'ppmp_pap.id = ppmp_activity.pap_id')
                ->leftJoin('ppmp_identifier', 'ppmp_identifier.id = ppmp_pap.identifier_id')
                ->leftJoin('ppmp_sub_program', 'ppmp_sub_program.id = ppmp_pap.sub_program_id')
                ->leftJoin('ppmp_program', 'ppmp_program.id = ppmp_pap.program_id')
                ->leftJoin('ppmp_organizational_outcome', 'ppmp_organizational_outcome.id = ppmp_pap.organizational_outcome_id')
                ->leftJoin('ppmp_cost_structure', 'ppmp_cost_structure.id = ppmp_pap.cost_structure_id')
                ->andWhere([
                    'ppmp_pr_item.pr_id' => $model->id,
                ])
                ->groupBy(['ppmp_activity.id'])
                ->asArray()
                ->all();
        
        $rccs = ArrayHelper::map($rccs, 'prexc', 'prexc');
        
        $items = PrItem::find()
                ->select([
                    'ppmp_pr_item.id as id',
                    's.id as ris_item_spec_id',
                    'ppmp_item.id as item_id',
                    'ppmp_item.title as item',
                    'ppmp_item.unit_of_measure as unit',
                    'ppmp_pr_item.cost as cost',
                    'sum(ppmp_pr_item.quantity) as total'
                ])
                ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                    s.activity_id = ppmp_ppmp_item.activity_id and 
                                                    s.item_id = ppmp_ppmp_item.item_id and 
                                                    s.cost = ppmp_pr_item.cost and 
                                                    s.type = ppmp_pr_item.type')
                ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                ->andWhere([
                    'ppmp_pr_item.pr_id' => $model->id,
                ])
                ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
                ->asArray()
                ->all();
        
        if(!empty($items))
        {
            foreach($items as $item)
            {
                $prItem = new PrItem();
                $prItem->id = $item['id'];
                $prItems[$item['id']] = $prItem;

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        return $this->renderAjax('_pr', [
            'model' => $model,
            'entityName' => $entityName,
            'fundCluster' => $fundCluster,
            'rccs' => $rccs,
            'items' => $items,
            'prItems' => $prItems,
            'specifications' => $specifications,
        ]);
    }

    public function actionPrintPr($id, $date_prepared)
    {
        $model = $this->findModel($id);
        $prItems = [];
        $specifications = [];
        $entityName = Settings::findOne(['title' => 'Entity Name']);
        $fundCluster = FundCluster::findOne($model->fund_cluster_id);
        $rccs = Pritem::find()
                ->select(['concat(
                    ppmp_cost_structure.code,"",
                    ppmp_organizational_outcome.code,"",
                    ppmp_program.code,"",
                    ppmp_sub_program.code,"",
                    ppmp_identifier.code,"",
                    ppmp_pap.code,"000-",
                    ppmp_activity.code
                ) as prexc',])
                ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                ->leftJoin('ppmp_activity', 'ppmp_activity.id = ppmp_ppmp_item.activity_id')
                ->leftJoin('ppmp_pap', 'ppmp_pap.id = ppmp_activity.pap_id')
                ->leftJoin('ppmp_identifier', 'ppmp_identifier.id = ppmp_pap.identifier_id')
                ->leftJoin('ppmp_sub_program', 'ppmp_sub_program.id = ppmp_pap.sub_program_id')
                ->leftJoin('ppmp_program', 'ppmp_program.id = ppmp_pap.program_id')
                ->leftJoin('ppmp_organizational_outcome', 'ppmp_organizational_outcome.id = ppmp_pap.organizational_outcome_id')
                ->leftJoin('ppmp_cost_structure', 'ppmp_cost_structure.id = ppmp_pap.cost_structure_id')
                ->andWhere([
                    'ppmp_pr_item.pr_id' => $model->id,
                ])
                ->groupBy(['ppmp_activity.id'])
                ->asArray()
                ->all();
        
        $rccs = ArrayHelper::map($rccs, 'prexc', 'prexc');
        
        $items = PrItem::find()
                ->select([
                    'ppmp_pr_item.id as id',
                    's.id as ris_item_spec_id',
                    'ppmp_item.id as item_id',
                    'ppmp_item.title as item',
                    'ppmp_item.unit_of_measure as unit',
                    'ppmp_pr_item.cost as cost',
                    'sum(ppmp_pr_item.quantity) as total'
                ])
                ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                    s.activity_id = ppmp_ppmp_item.activity_id and 
                                                    s.item_id = ppmp_ppmp_item.item_id and 
                                                    s.cost = ppmp_pr_item.cost and 
                                                    s.type = ppmp_pr_item.type')
                ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                ->andWhere([
                    'ppmp_pr_item.pr_id' => $model->id,
                ])
                ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
                ->asArray()
                ->all();
        
        if(!empty($items))
        {
            foreach($items as $item)
            {
                $prItem = new PrItem();
                $prItem->id = $item['id'];
                $prItems[$item['id']] = $prItem;

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        return $this->renderAjax('_file-pr', [
            'model' => $model,
            'entityName' => $entityName,
            'fundCluster' => $fundCluster,
            'rccs' => $rccs,
            'items' => $items,
            'prItems' => $prItems,
            'specifications' => $specifications,
            'date_prepared' => $date_prepared,
        ]);
    }

    public function actionDbmItems($id)
    {
        $model = $this->findModel($id);

        return $this->renderAjax('_dbm-items', [
            'model' => $model,
        ]);
    }

    public function actionDbmPricing($id)
    {
        $model = $this->findModel($id);

        return $this->renderAjax('_dbm-pricing', [
            'model' => $model,
        ]);
    }

    public function actionViewDbmPrice($id)
    {
        $model = $this->findModel($id);

        $aprItems = [];
        $specifications = [];
        $costModels = [];

        $supplier = Supplier::findOne(['id' => 1]);

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $aprItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total',
                'ppmp_pr_item_cost.cost as price'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_pr_item_cost', 'ppmp_pr_item_cost.pr_item_id = ppmp_pr_item.id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();

        $itemIDs = ArrayHelper::map($aprItems, 'id', 'id');
        
        if(!empty($aprItems))
        {
            foreach($aprItems as $item)
            {
                $cost = PrItemCost::findOne(['pr_item_id' => $item['id']]) ? PrItemCost::findOne(['pr_item_id' => $item['id']]) : new PrItemCost();
                $cost->pr_id = $model->id;
                $cost->pr_item_id = $item['id'];
                $cost->supplier_id = $supplier->id;

                $costModels[$item['id']] = $cost; 

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        if(MultipleModel::loadMultiple($costModels, Yii::$app->request->post()))
        {
            if(!empty($costModels))
            {
                foreach($costModels as $costModel)
                {
                    $item = PrItem::findOne($costModel->pr_item_id);

                    $includedItems = PrItem::find()
                        ->select(['ppmp_pr_item.id as id'])
                        ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                        ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                        ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                        ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                        ->andWhere([
                            'ppmp_pr_item.pr_id' => $item->pr_id,
                            'ppmp_item.id' => $item->ppmpItem->item_id,
                            'ppmp_pr_item.cost' => $item->cost,
                        ])
                        ->all();
                    
                    $includedItems = ArrayHelper::map($includedItems, 'id', 'id');

                    if(!empty($includedItems))
                    {
                        foreach($includedItems as $includedItem)
                        {
                            $cost = new PrItemCost();
                            $cost->pr_id = $costModel->pr_id;
                            $cost->pr_item_id = $includedItem;
                            $cost->supplier_id = 1;
                            $cost->cost = $costModel->cost;
                            $cost->save();
                        }
                    }

                    $itemCost = ItemCost::findOne(['source_model' => 'PrItemCost', 'source_id' => $cost->id]) ? ItemCost::findOne(['source_model' => 'PrItemCost', 'source_id' => $cost->id]) : new ItemCost();
                    $itemCost->item_id = $item->ppmpItem->item_id;
                    $itemCost->cost = $cost->cost;
                    $itemCost->source_model = 'PrItemCost';
                    $itemCost->source_id = $costModel->id;
                    $itemCost->save();
                }
            }
        }

        return $this->renderAjax('_dbm-pricing-form', [
            'model' => $model,
            'aprItems' => $aprItems,
            'costModels' => $costModels,
            'specifications' => $specifications,
            'supplier' => $supplier,
            'itemIDs' => $itemIDs,
        ]);
    }

    public function actionAprItems($id)
    {
        $model = $this->findModel($id);

        $aprItems = [];
        $specifications = [];

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $forAprs = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();

        if(!empty($forAprs))
        {
            foreach($forAprs as $item)
            {
                $aprItem = new AprItem();
                $aprItem->id = $item['id'];
                $aprItems[$item['id']] = $aprItem;

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        if(MultipleModel::loadMultiple($aprItems, Yii::$app->request->post()))
        {
            $aprSupplyOfficer = Settings::findOne(['title' => 'APR Supply Officer']);
            $aprFundsCertifier = Settings::findOne(['title' => 'APR Funds Certifier']);
            $aprApprover = Settings::findOne(['title' => 'APR Approver']);

            $apr = Apr::findOne(['pr_id' => $model->id]) ? Apr::findOne(['pr_id' => $model->id]) : new Apr();
            $apr->pr_id = $model->id;
            $apr->stock_certified_by = $aprSupplyOfficer->value;
            $apr->fund_certified_by = $aprFundsCertifier->value;
            $apr->approved_by = $aprApprover->value;
            $apr->save();

            $selectedPrItems = Yii::$app->request->post('AprItem');
          
            if(!empty($selectedPrItems))
            {
                foreach($selectedPrItems as $prItem)
                {
                    if($prItem['id'] != 0)
                    {
                        $item = PrItem::findOne($prItem['id']);
  
                        $includedItems = PrItem::find()
                            ->select(['ppmp_pr_item.id as id'])
                            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                            ->andWhere([
                                'ppmp_pr_item.pr_id' => $item->pr_id,
                                'ppmp_item.id' => $item->ppmpItem->item_id,
                                'ppmp_pr_item.cost' => $item->cost,
                            ])
                            ->all();
                        
                        $includedItems = ArrayHelper::map($includedItems, 'id', 'id');

                        if(!empty($includedItems))
                        {
                            foreach($includedItems as $includedItem)
                            {
                                $aprItem = AprItem::findOne(['apr_id' => $apr->id, 'pr_item_id' => $includedItem]);
                                if($aprItem->delete())
                                {
                                    $cost = PrItemCost::findOne(['id' => $includedItem]);
                                    if($cost)
                                    {
                                        $itemCost = ItemCost::findOne(['source_model' => 'PrItemCost', 'source_id' => $cost->id]);
                                        if($itemCost)
                                        {
                                            $itemCost->delete();
                                        }

                                        $cost->delete();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->renderAjax('_apr-items', [
            'model' => $model,
            'forAprs' => $forAprs,
            'aprItems' => $aprItems,
            'specifications' => $specifications,
        ]);
    }

    public function actionRfqItems($id)
    {
        $model = $this->findModel($id);

        $rfqItems = [];
        $specifications = [];

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $forRfqs = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['not in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();
        
        if(!empty($forRfqs))
        {
            foreach($forRfqs as $item)
            {

                $rfqItem = new AprItem();
                $rfqItem->id = $item['id'];
                $rfqItems[$item['id']] = $rfqItem;

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        if(MultipleModel::loadMultiple($rfqItems, Yii::$app->request->post()))
        {
            $aprSupplyOfficer = Settings::findOne(['title' => 'APR Supply Officer']);
            $aprFundsCertifier = Settings::findOne(['title' => 'APR Funds Certifier']);
            $aprApprover = Settings::findOne(['title' => 'APR Approver']);

            $apr = Apr::findOne(['pr_id' => $model->id]) ? Apr::findOne(['pr_id' => $model->id]) : new Apr();
            $apr->pr_id = $model->id;
            $apr->stock_certified_by = $aprSupplyOfficer->value;
            $apr->fund_certified_by = $aprFundsCertifier->value;
            $apr->approved_by = $aprApprover->value;
            $apr->save();

            $selectedPrItems = Yii::$app->request->post('AprItem');
          
            if(!empty($selectedPrItems))
            {
                foreach($selectedPrItems as $prItem)
                {
                    if($prItem['id'] != 0)
                    {
                        $item = PrItem::findOne($prItem['id']);
  
                        $includedItems = PrItem::find()
                            ->select(['ppmp_pr_item.id as id'])
                            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                            ->andWhere([
                                'ppmp_pr_item.pr_id' => $item->pr_id,
                                'ppmp_item.id' => $item->ppmpItem->item_id,
                                'ppmp_pr_item.cost' => $item->cost,
                            ])
                            ->all();
                        
                        $includedItems = ArrayHelper::map($includedItems, 'id', 'id');

                        if(!empty($includedItems))
                        {
                            foreach($includedItems as $includedItem)
                            {
                                $aprItem = new AprItem();
                                $aprItem->apr_id = $apr->id;
                                $aprItem->pr_item_id = $includedItem;

                                if($aprItem->save())
                                {
                                    $cost = PrItemCost::findOne(['id' => $includedItem]);
                                    if($cost)
                                    {
                                        $itemCost = ItemCost::findOne(['source_model' => 'PrItemCost', 'source_id' => $cost->id]);
                                        if($itemCost)
                                        {
                                            $itemCost->delete();
                                        }

                                        $cost->delete();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $this->renderAjax('_rfq-items', [
            'model' => $model,
            'forRfqs' => $forRfqs,
            'rfqItems' => $rfqItems,
            'specifications' => $specifications,
        ]);
    }

    public function actionApr($id)
    {
        $model = $this->findModel($id);
        $agency = Settings::findOne(['title' => 'Agency Name']);
        $regionalOffice = Settings::findOne(['title' => 'Regional Office']);
        $address = Settings::findOne(['title' => 'Address']);
        $shortName = Settings::findOne(['title' => 'Agency Short Name']);
        $apr = Apr::findOne(['pr_id' => $model->id]);
        $supplier = Supplier::findOne(['id' => 1]);
        $specifications = [];

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $aprItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();

        if(!empty($aprItems))
        {
            foreach($aprItems as $item)
            {
                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        return $this->renderAjax('_apr',[
            'model' => $model,
            'agency' => $agency,
            'regionalOffice' => $regionalOffice,
            'address' => $address,
            'apr' => $apr,
            'aprItems' => $aprItems,
            'supplier' => $supplier,
            'specifications' => $specifications,
            'shortName' => $shortName,
        ]);
    }

    public function actionPrintApr($id, $rad_no, $rad_month, $rad_year, $pl_month, $pl_year, $telefax, $check_1, $check_2, $check_3, $check_4, $check_5, $check_6, $other, $date_generated)
    {
        $model = $this->findModel($id);
        $agency = Settings::findOne(['title' => 'Agency Name']);
        $regionalOffice = Settings::findOne(['title' => 'Regional Office']);
        $address = Settings::findOne(['title' => 'Address']);
        $shortName = Settings::findOne(['title' => 'Agency Short Name']);
        $apr = Apr::findOne(['pr_id' => $model->id]);
        $supplier = Supplier::findOne(['id' => 1]);
        $specifications = [];

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $aprItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();

        if(!empty($aprItems))
        {
            foreach($aprItems as $item)
            {
                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        return $this->renderAjax('_file-apr',[
            'model' => $model,
            'agency' => $agency,
            'regionalOffice' => $regionalOffice,
            'address' => $address,
            'apr' => $apr,
            'aprItems' => $aprItems,
            'supplier' => $supplier,
            'specifications' => $specifications,
            'shortName' => $shortName,
            'rad_no' => $rad_no,
            'rad_month' => $rad_month,
            'rad_year' => $rad_year,
            'pl_month' => $pl_month,
            'pl_year' => $pl_year,
            'telefax' => $telefax,
            'check_1' => $check_1,
            'check_2' => $check_2,
            'check_3' => $check_3,
            'check_4' => $check_4,
            'check_5' => $check_5,
            'check_6' => $check_6,
            'other' => $other,
            'date_generated' => $date_generated
        ]);
    }

    public function actionQuotation($id)
    {
        $model = $this->findModel($id);

        $rfqs = Rfq::findAll(['pr_id' => $model->id]);

        $items = [];

        if($rfqs)
        {
            foreach($rfqs as $key => $rfq)
            {
                $items[$key]['label'] = '<table style="width:100%;" id="rfq-table-'.$rfq->id.'">';
                $items[$key]['label'] .= '<tr>';
                $items[$key]['label'] .= '<td><a href="javascript:void(0);" onclick="loadRfq('.$rfq->id.')">RFQ No. '.$rfq->rfq_no.'</a></td>';
                $items[$key]['label'] .= '<td align=right>';
                $items[$key]['label'] .= '<a href="javascript:void(0);" onclick="printRfq('.$rfq->id.');"><i class="fa fa-print"></i></a>&nbsp;&nbsp;';
                $items[$key]['label'] .=  Html::button('<i class="fa fa-edit"></i>', ['value' => Url::to(['/v1/pr/update-rfq', 'id' => $rfq->id]), 'class' => 'update-rfq-button button-link']).'&nbsp;&nbsp;';
                $items[$key]['label'] .= '<a href="javascript:void(0);" onclick="deleteRfq('.$model->id.','.$rfq->id.');" data-confirm="Are you sure you want to delete this item?" data-method="post"><i class="fa fa-trash"></i></a>';
                $items[$key]['label'] .= '</td>';
                $items[$key]['label'] .= '</tr>';
                $items[$key]['label'] .= '</table>';
                $items[$key]['content'] = '<div id="rfq-content-'.$rfq->id.'"></div>';
                $items[$key]['options'] = ['class' => 'panel panel-info'];
            }
        }

        return $this->renderAjax('_quotation', [
            'model' => $model,
            'rfqs' => $rfqs,
            'items' => $items,
        ]);
    }

    public function actionViewRfq($id)
    {
        $rfq = Rfq::findOne(['id' => $id]);

        $model = $rfq->pr;

        $bac = Settings::findOne(['title' => 'BAC Chairperson']);
        $agency = Settings::findOne(['title' => 'Agency Name']);
        $regionalOffice = Settings::findOne(['title' => 'Regional Office']);
        $address = Settings::findOne(['title' => 'Address']);
        $email = Settings::findOne(['title' => 'Email']);
        $telephoneNos = Settings::findOne(['title' => 'Telephone Nos.']);
        $bacChairperson = Signatory::findOne(['emp_id' => $bac->value]);

        $specifications = [];
        $forContractItems = [];

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $rfqItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['not in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();
        
        if(!empty($rfqItems))
        {
            foreach($rfqItems as $item)
            {
                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }

                $forContractItem = ForContractItem::findOne(['item_id' => $item['item_id']]);
                if($forContractItem){ $forContractItems[$item['id']] = $forContractItem; }
            }
        }

        return $this->renderAjax('_rfq', [
            'model' => $model,
            'rfq' => $rfq,
            'rfqItems' => $rfqItems,
            'specifications' => $specifications,
            'bacChairperson' => $bacChairperson,
            'agency' => $agency,
            'regionalOffice' => $regionalOffice,
            'address' => $address,
            'email' => $email,
            'telephoneNos' => $telephoneNos,
            'forContractItems' => $forContractItems,
        ]);
    }
    
    public function actionPrintRfq($id)
    {
        $rfq = Rfq::findOne(['id' => $id]);

        $model = $rfq->pr;

        $bac = Settings::findOne(['title' => 'BAC Chairperson']);
        $agency = Settings::findOne(['title' => 'Agency Name']);
        $regionalOffice = Settings::findOne(['title' => 'Regional Office']);
        $address = Settings::findOne(['title' => 'Address']);
        $email = Settings::findOne(['title' => 'Email']);
        $telephoneNos = Settings::findOne(['title' => 'Telephone Nos.']);

        $bacChairperson = Signatory::findOne(['emp_id' => $bac->value]);

        $specifications = [];
        $forContractItems = [];

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $rfqItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total'
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['not in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();
        
        if(!empty($rfqItems))
        {
            foreach($rfqItems as $item)
            {
                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }

                $forContractItem = ForContractItem::findOne(['item_id' => $item['item_id']]);
                if($forContractItem){ $forContractItems[$item['id']] = $forContractItem; }
            }
        }

        return $this->renderAjax('_file-rfq', [
            'model' => $model,
            'rfq' => $rfq,
            'rfqItems' => $rfqItems,
            'specifications' => $specifications,
            'bacChairperson' => $bacChairperson,
            'agency' => $agency,
            'regionalOffice' => $regionalOffice,
            'address' => $address,
            'email' => $email,
            'telephoneNos' => $telephoneNos,
            'forContractItems' => $forContractItems,
        ]);
    }

    public function actionDeleteRfq($id, $rfq_id)
    {
        $model = $this->findModel($id);

        $rfq = Rfq::findOne(['id' => $rfq_id]);

        if($rfq->delete())
        {
            
        }
    }

    public function actionGenerateRfq($id)
    {
        $model = $this->findModel($id);

        $rfqModel = new Rfq();
        $rfqModel->pr_id = $model->id;

        if($rfqModel->load(Yii::$app->request->post()))
        {
            $time = str_pad($rfqModel->deadline_time, 2, '0', STR_PAD_LEFT).':'.str_pad($rfqModel->minute, 2, '0', STR_PAD_LEFT).' '.$rfqModel->meridian;
            $lastRfq = Rfq::find()->orderBy(['id' => SORT_DESC])->one();
            $lastNumber = $lastRfq ? intval(substr($lastRfq->rfq_no, -3)) : '001';
            $rfqModel->rfq_no = $lastRfq ? substr(date("Y"), -2).'-'.date("m").'-'.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT) : substr(date("Y"), -2).'-'.date("m").'-'.$lastNumber;
            $rfqModel->deadline_time = $time;
            $rfqModel->save();
        }

        return $this->renderAjax('_rfq-form', [
            'model' => $model,
            'rfqModel' => $rfqModel,
        ]);
    }

    public function actionUpdateRfq($id)
    {
        $rfqModel = Rfq::findOne($id);

        $model = $rfqModel->pr;
        $timeArray = explode(" ",$rfqModel->deadline_time);
        $time = explode(":", $timeArray[0]);
        $rfqModel->deadline_time = $time[0];
        $rfqModel->minute = isset($time[1]) ? $time[1] : '00';
        $rfqModel->meridian = isset($timeArray[1]) ? $timeArray[1] : 'AM';

        if($rfqModel->load(Yii::$app->request->post()))
        {
            $time = str_pad($rfqModel->deadline_time, 2, '0', STR_PAD_LEFT).':'.str_pad($rfqModel->minute, 2, '0', STR_PAD_LEFT).' '.$rfqModel->meridian;
            $rfqModel->deadline_time = $time;
            $rfqModel->save();
        }

        return $this->renderAjax('_rfq-form', [
            'model' => $model,
            'rfqModel' => $rfqModel,
        ]);
    }

    public function actionRfq($id)
    {
        $model = $this->findModel($id);

        $rfqs = Rfq::findAll(['pr_id' => $model->id]);

        $items = [];

        if($rfqs)
        {
            foreach($rfqs as $key => $rfq)
            {
                $bac = Settings::findOne(['title' => 'BAC Chairperson']);
                $agency = Settings::findOne(['title' => 'Agency Name']);
                $regionalOffice = Settings::findOne(['title' => 'Regional Office']);
                $address = Settings::findOne(['title' => 'Address']);
                $email = Settings::findOne(['title' => 'Email']);
                $telephoneNos = Settings::findOne(['title' => 'Telephone Nos.']);

                $bacChairperson = Signatory::findOne(['emp_id' => $bac->value]);

                $specifications = [];
                $forContractItems = [];

                $aprItemIDs = AprItem::find()
                            ->select(['pr_item_id'])
                            ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                            ->where(['pr_id' => $model->id])
                            ->asArray()
                            ->all();

                $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

                $rfqItems = PrItem::find()
                    ->select([
                        'ppmp_pr_item.id as id',
                        's.id as ris_item_spec_id',
                        'ppmp_item.id as item_id',
                        'ppmp_item.title as item',
                        'ppmp_item.unit_of_measure as unit',
                        'ppmp_pr_item.cost as cost',
                        'sum(ppmp_pr_item.quantity) as total'
                    ])
                    ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                    ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                    ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                    ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                        s.activity_id = ppmp_ppmp_item.activity_id and 
                                                        s.item_id = ppmp_ppmp_item.item_id and 
                                                        s.cost = ppmp_pr_item.cost and 
                                                        s.type = ppmp_pr_item.type')
                    ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                    ->andWhere([
                        'ppmp_pr_item.pr_id' => $model->id,
                    ])
                    ->andWhere(['not in', 'ppmp_pr_item.id', $aprItemIDs])
                    ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
                    ->asArray()
                    ->all();

                    if(!empty($rfqItems))
                    {
                        foreach($rfqItems as $item)
                        {
                            $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                            if($specs){ $specifications[$item['id']] = $specs; }
            
                            $forContractItem = ForContractItem::findOne(['item_id' => $item['item_id']]);
                            if($forContractItem){ $forContractItems[$item['id']] = $forContractItem; }
                        }
                    }

                $items[$key]['label'] = '<table style="width:100%;" id="rfq-table-modal-'.$rfq->id.'">';
                $items[$key]['label'] .= '<tr>';
                $items[$key]['label'] .= '<td>RFQ No. '.$rfq->rfq_no.'</td>';
                $items[$key]['label'] .= '<td align=right>';
                $items[$key]['label'] .= '<a href="javascript:void(0);" onclick="printRfq('.$rfq->id.');"><i class="fa fa-print"></i></a>&nbsp;&nbsp;';
                $items[$key]['label'] .= '</td>';
                $items[$key]['label'] .= '</tr>';
                $items[$key]['label'] .= '</table>';
                $items[$key]['content'] = $this->renderAjax('_rfq', [
                    'model' => $model,
                    'rfq' => $rfq,
                    'rfqItems' => $rfqItems,
                    'specifications' => $specifications,
                    'bacChairperson' => $bacChairperson,
                    'agency' => $agency,
                    'regionalOffice' => $regionalOffice,
                    'address' => $address,
                    'email' => $email,
                    'telephoneNos' => $telephoneNos,
                    'forContractItems' => $forContractItems,
                ]);
                $items[$key]['options'] = ['class' => 'panel panel-info'];
            }
        }

        return $this->renderAjax('_rfq-modal', [
            'model' => $model,
            'rfqs' => $rfqs,
            'items' => $items,
        ]);
    }

    public function actionRetrieveQuotation($id)
    {
        $model = $this->findModel($id);

        $rfqs = Rfq::findAll(['pr_id' => $model->id]);

        $aprItemIDs = AprItem::find()
                    ->select(['pr_item_id'])
                    ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
                    ->where(['pr_id' => $model->id])
                    ->asArray()
                    ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $supplierIDs = PRItemCost::find()->select(['supplier_id'])->andWhere(['pr_id' => $model->id])->andWhere(['<>', 'supplier_id', 1])->groupBy(['supplier_id'])->asArray()->all();
        $supplierIDs = ArrayHelper::map($supplierIDs, 'supplier_id', 'supplier_id');

        $rfqItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total',
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['not in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();
        
        $rfqItemIDs = ArrayHelper::map($rfqItems, 'id', 'id');
        
        $rfqItemCosts = PrItemCost::find()->where(['in', 'pr_item_id', $rfqItemIDs])->asArray()->all();

        return $this->renderAjax('_retrieve-quotation', [
            'model' => $model,
            'rfqs' => $rfqs,
        ]);
    }

    public function actionRetrieveRfq($id)
    {
        $model = $this->findModel($id);

        $rfqInfoModel = new RfqInfo();

        $existingSupplierIDs = PRItemCost::find()->select(['supplier_id'])->andWhere(['pr_id' => $model->id])->andWhere(['<>', 'supplier_id', 1])->groupBy(['supplier_id'])->asArray()->all();
        $existingSupplierIDs = ArrayHelper::map($existingSupplierIDs, 'supplier_id', 'supplier_id');

        $suppliers = Supplier::find()->select(['id', 'concat(business_name," (",business_address,")") as title'])->where(['not in', 'id', $existingSupplierIDs])->andWhere(['<>', 'id', 1])->asArray()->all();
        $suppliers = ArrayHelper::map($suppliers, 'id', 'title');

        $rfqs = Rfq::find()->select(['id', 'concat("RFQ No. ",rfq_no) as title'])->where(['pr_id' => $model->id])->asArray()->all();
        $rfqs = ArrayHelper::map($rfqs, 'id', 'title');

        $aprItemIDs = AprItem::find()
            ->select(['pr_item_id'])
            ->leftJoin('ppmp_apr', 'ppmp_apr.id = ppmp_apr_item.apr_id')
            ->where(['pr_id' => $model->id])
            ->asArray()
            ->all();

        $aprItemIDs = ArrayHelper::map($aprItemIDs, 'pr_item_id', 'pr_item_id');

        $rfqItems = PrItem::find()
            ->select([
                'ppmp_pr_item.id as id',
                's.id as ris_item_spec_id',
                'ppmp_item.id as item_id',
                'ppmp_item.title as item',
                'ppmp_item.unit_of_measure as unit',
                'ppmp_pr_item.cost as cost',
                'sum(ppmp_pr_item.quantity) as total',
            ])
            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
            ->leftJoin('ppmp_ris_item_spec s', 's.ris_id = ppmp_ris.id and 
                                                s.activity_id = ppmp_ppmp_item.activity_id and 
                                                s.item_id = ppmp_ppmp_item.item_id and 
                                                s.cost = ppmp_pr_item.cost and 
                                                s.type = ppmp_pr_item.type')
            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
            ->andWhere([
                'ppmp_pr_item.pr_id' => $model->id,
            ])
            ->andWhere(['not in', 'ppmp_pr_item.id', $aprItemIDs])
            ->groupBy(['ppmp_item.id', 's.id', 'ppmp_pr_item.cost'])
            ->asArray()
            ->all();
        
        $itemIDs = ArrayHelper::map($rfqItems, 'id', 'id');
        
        $costModels = [];
        $specifications = [];

        if(!empty($rfqItems))
        {
            foreach($rfqItems as $item)
            {
                $cost = new PrItemCost();
                $cost->pr_id = $model->id;
                $cost->pr_item_id = $item['id'];

                $costModels[$item['id']] = $cost;

                $specs = RisItemSpec::findOne(['id' => $item['ris_item_spec_id']]);
                if($specs){ $specifications[$item['id']] = $specs; }
            }
        }

        if($rfqInfoModel->load(Yii::$app->request->post()) && MultipleModel::loadMultiple($costModels, Yii::$app->request->post()))
        {
            if($rfqInfoModel->save())
            {
                if(!empty($costModels))
                {
                    foreach($costModels as $costModel)
                    {
                        $item = PrItem::findOne($costModel->pr_item_id);
  
                        $includedItems = PrItem::find()
                            ->select(['ppmp_pr_item.id as id'])
                            ->leftJoin('ppmp_ris', 'ppmp_ris.id = ppmp_pr_item.ris_id')
                            ->leftJoin('ppmp_ris_item', 'ppmp_ris_item.id = ppmp_pr_item.ris_item_id')
                            ->leftJoin('ppmp_ppmp_item', 'ppmp_ppmp_item.id = ppmp_pr_item.ppmp_item_id')
                            ->leftJoin('ppmp_item', 'ppmp_item.id = ppmp_ppmp_item.item_id')
                            ->andWhere([
                                'ppmp_pr_item.pr_id' => $item->pr_id,
                                'ppmp_item.id' => $item->ppmpItem->item_id,
                                'ppmp_pr_item.cost' => $item->cost,
                            ])
                            ->all();
                        
                        $includedItems = ArrayHelper::map($includedItems, 'id', 'id');

                        if(!empty($includedItems))
                        {
                            foreach($includedItems as $includedItem)
                            {
                                $cost = new PrItemCost();
                                $cost->pr_id = $model->id;
                                $cost->pr_item_id = $includedItem;
                                $cost->supplier_id = $rfqInfoModel->supplier_id;
                                $cost->rfq_id = $rfqInfoModel->rfq_id;
                                $cost->cost = $costModel->cost;
                                $cost->save();
                            }
                        }

                        $itemCost = ItemCost::findOne(['source_model' => 'PrItemCost', 'source_id' => $cost->id]) ? ItemCost::findOne(['source_model' => 'PrItemCost', 'source_id' => $cost->id]) : new ItemCost();
                        $itemCost->item_id = $item->ppmpItem->item_id;
                        $itemCost->cost = $cost->cost;
                        $itemCost->source_model = 'PrItemCost';
                        $itemCost->source_id = $costModel->id;
                        $itemCost->save();
                    }
                }
            }
        }

        return $this->renderAjax('_retrieve-rfq-form', [
            'model' => $model,
            'rfqInfoModel' => $rfqInfoModel,
            'rfqs' => $rfqs,
            'rfqItems' => $rfqItems,
            'suppliers' => $suppliers,
            'costModels' => $costModels,
            'specifications' => $specifications,
            'itemIDs' => $itemIDs,
        ]);
    }

    /**
     * Creates a new Pr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pr();

        $approver = Settings::findOne(['title' => 'PR Approver']);
        $model->approved_by = $approver ? $approver->value : '';

        $offices = Office::find()->all();
        $offices = ArrayHelper::map($offices, 'abbreviation', 'abbreviation');

        $fundSources = FundSource::find()->all();
        $fundSources = ArrayHelper::map($fundSources, 'id', 'code');

        $fundClusters = FundCluster::find()->all();
        $fundClusters = ArrayHelper::map($fundClusters, 'id', 'title');

        $signatories = Signatory::find()->all();
        $signatories = ArrayHelper::map($signatories, 'emp_id', 'name');

        $years = Ppmp::find()->select(['distinct(year) as year'])->where(['stage' => 'Final'])->orderBy(['year' => SORT_DESC])->asArray()->all();
        $years = ArrayHelper::map($years, 'year', 'year');

        $procurementModes = ProcurementMode::find()->all();
        $procurementModes = ArrayHelper::map($procurementModes, 'id', 'title');

        $types = [
            'Supply' => 'Goods',
            'Service' => 'Service/Contract',
        ];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $lastPr = Pr::find()->orderBy(['id' => SORT_DESC])->one();
            $lastNumber = $lastPr ? intval(substr($lastPr->pr_no, -3)) : '001';
            $pr_no = $lastPr ? substr(date("Y"), -2).'-'.date("m").'-'.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT) : substr(date("Y"), -2).'-'.date("m").'-'.$lastNumber;
            $model->pr_no = $pr_no;
            $model->created_by = Yii::$app->user->identity->userinfo->EMP_N; 
            $model->date_created = date("Y-m-d"); 
            $model->save();

            \Yii::$app->getSession()->setFlash('success', 'Record Saved');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'offices' => $offices,
            'fundSources' => $fundSources,
            'fundClusters' => $fundClusters,
            'signatories' => $signatories,
            'types' => $types,
            'years' => $years,
            'procurementModes' => $procurementModes
        ]);
    }

    /**
     * Updates an existing Pr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $offices = Office::find()->all();
        $offices = ArrayHelper::map($offices, 'abbreviation', 'abbreviation');

        $fundSources = FundSource::find()->all();
        $fundSources = ArrayHelper::map($fundSources, 'id', 'code');

        $fundClusters = FundCluster::find()->all();
        $fundClusters = ArrayHelper::map($fundClusters, 'id', 'title');

        $signatories = Signatory::find()->all();
        $signatories = ArrayHelper::map($signatories, 'emp_id', 'name');

        $years = Ppmp::find()->select(['distinct(year) as year'])->where(['stage' => 'Final'])->orderBy(['year' => SORT_DESC])->asArray()->all();
        $years = ArrayHelper::map($years, 'year', 'year');

        $procurementModes = ProcurementMode::find()->all();
        $procurementModes = ArrayHelper::map($procurementModes, 'id', 'title');

        $types = [
            'Supply' => 'Goods',
            'Service' => 'Service/Contract',
        ];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            \Yii::$app->getSession()->setFlash('success', 'Record Updated');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'offices' => $offices,
            'fundSources' => $fundSources,
            'fundClusters' => $fundClusters,
            'signatories' => $signatories,
            'types' => $types,
            'years' => $years,
            'procurementModes' => $procurementModes
        ]);
    }

    /**
     * Deletes an existing Pr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->delete())
        {
            $statuses = Transaction::deleteAll(['model' => 'Pr', 'model_id' => $id]);
        }
        
        \Yii::$app->getSession()->setFlash('success', 'Record Deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Pr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
