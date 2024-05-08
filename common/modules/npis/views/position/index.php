<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
use common\modules\npis\models\Competency;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\positionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Positions';
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="position-index">
    <?= $this->render('_search', [
        'model' => $searchModel,
        'divisions' => $divisions
    ]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Position Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('position-item-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>
            <br>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'position-table'
                ],
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                    ],
                    [
                        'attribute' => 'item_no',
                        'header' => 'PLANTILLA ITEM NO.',
                    ],
                    [
                        'attribute' => 'position_id',
                        'header' => 'POSITION TITLE',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'division_id',
                        'header' => 'DIVISION',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'grade',
                        'header' => 'SALARY GRADE',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'step',
                        'header' => 'STEP',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'status',
                        'header' => 'STATUS',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            return $model->status == 1 ? 'Active' : 'Inactive';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '&nbsp;',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'template' => '<center>{view}</center>',
                        'buttons' => [
                            'view' => function($url, $model, $key){
                                return Yii::$app->user->can('position-item-view') ? Html::a('Set Competency', ['view', 'id' => $model->item_no], [
                                    'class' => 'btn btn-primary btn-xs'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '&nbsp;',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'template' => '<center>{update}</center>',
                        'buttons' => [
                            'update' => function($url, $model, $key){
                                return Yii::$app->user->can('position-item-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->item_no], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                ]
            ]); ?>

        </div>
    </div>
</div>

<?php
if ($successMessage) {
    $this->registerJs("
        $(document).ready(function() {
            // Display the flash message
            $('.alert-success').fadeIn();

            // Hide the flash message after 5 seconds
            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 3000);
        });
    ");
}
?>
<style>
.isChecked {
  background-color: #F5F5F5 !important;
}
.bold-style {
    font-weight: bolder !important;
}
tr{
  background-color: white;
}
/* click-through element */
.check-position-item {
  pointer-events: none !important;
}

#position-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#position-table > thead > tr > td,
#position-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>