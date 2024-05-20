<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\special-orderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Special Order';
$this->params['breadcrumbs'][] = 'NPIS';
$this->params['breadcrumbs'][] = 'Staff 201';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="special-order-index">
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Special Order Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('special-order-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'special-order-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'special-order-table'
                ],
                'dataProvider' => $dataProvider,
                'columns' => Yii::$app->user->can('HR') ? [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                    ],
                    [
                        'header' => 'NAME OF STAFF',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->employee->name;
                        }
                    ],
                    [
                        'attribute' => 'subject',
                        'header' => 'SUBJECT',
                    ],
                    [
                        'attribute' => 'from_date',
                        'header' => 'DATE ISSUED',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'header' => 'ATTACHMENT',
                        'format' => 'raw',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            $str = '';
                            $str .= $model->filePath != '' ? $model->filePath.'<br>' : '';
                            if($model->files){
                                foreach($model->files as $file){
                                    $str.= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id]).'<br>';
                                }
                            }
                            return $str;
                        }
                    ],
                    [
                        'attribute' => 'approver',
                        'header' => 'VERIFIED BY',
                        'headerOptions' => [
                            'style' => 'font-weight: bolder !important;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'header' => '&nbsp;',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model, $key, $index) use ($dataProvider){
                            $pagination = $dataProvider->getPagination();

                            if ($pagination !== false) {
                                // Calculate the index based on the current page and page size
                                $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                            } else {
                                // If pagination is disabled, just use the $index directly
                                $index += 1;
                            }

                            return Yii::$app->user->can('special-order-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'emp_id' => $model->emp_id, 'subject' => $model->subject, 'from_date' => $model->from_date, 'idx' => $index], [
                                'class' => 'btn btn-link'
                            ]) : '';
                        }
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-special-order-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model, $key, $index) use ($dataProvider, $form, $specialOrderModels){
                            $pagination = $dataProvider->getPagination();
                            if ($pagination !== false) {
                                // Calculate the index based on the current page and page size
                                $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                            } else {
                                // If pagination is disabled, just use the $index directly
                                $index += 1;
                            }

                            return Yii::$app->user->can('special-order-delete') ? $form->field($specialOrderModels[$index], "[$index]id")->checkbox([
                                'value' => $index,
                                'class' => 'check-special-order-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ] : [
                    [
                        'class' => 'yii\grid\SerialColumn',
                    ],
                    [
                        'attribute' => 'subject',
                        'header' => 'SUBJECT',
                    ],
                    [
                        'attribute' => 'from_date',
                        'header' => 'DATE ISSUED',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'header' => 'ATTACHMENT',
                        'format' => 'raw',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            $str = '';
                            if($model->files){
                                foreach($model->files as $file){
                                    $str.= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id]).'<br>';
                                }
                            }
                            return $str;
                        }
                    ],
                    [
                        'attribute' => 'approver',
                        'header' => 'VERIFIED BY',
                        'headerOptions' => [
                            'style' => 'font-weight: bolder !important;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                ],
            ]); ?>

        <div class="form-group pull-right"> 
            <?= Yii::$app->user->can('special-order-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-special-order-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
        </div>
        <div class="clearfix"></div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
    $script = '
    function enableRemoveButton()
    {
        $("#special-order-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-special-order-button").attr("disabled", false) : $("#delete-selected-special-order-button").attr("disabled", true);
    }

    $(".check-special-order-items").change(function() {
        $(".check-special-order-item").prop("checked", $(this).prop("checked"));
        $("#special-order-table tr").toggleClass("isChecked", $(".check-special-order-item").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-special-order-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#special-order-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#special-order-table tr").each(function() {
            var checkbox = $(this).find(".check-special-order-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-special-order-item").removeAttr("checked");
        enableRemoveButton();
    });

    ';

    $this->registerJs($script, View::POS_END);
?>

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
.check-special-order-item {
  pointer-events: none !important;
}

#special-order-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#special-order-table > thead > tr > td,
#special-order-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>