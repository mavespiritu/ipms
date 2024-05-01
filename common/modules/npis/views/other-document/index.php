<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\other-documentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Other Documents';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="other-document-index">
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Other Documents Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('other-document-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'other-document-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'other-document-table'
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

                            return Yii::$app->user->can('other-document-update') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'emp_id' => $model->emp_id, 'subject' => $model->subject, 'from_date' => $model->from_date, 'idx' => $index], [
                                'class' => 'btn btn-link'
                            ]) : '';
                        }
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-other-document-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model, $key, $index) use ($dataProvider, $form, $otherDocumentModels){
                            $pagination = $dataProvider->getPagination();
                            if ($pagination !== false) {
                                // Calculate the index based on the current page and page size
                                $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                            } else {
                                // If pagination is disabled, just use the $index directly
                                $index += 1;
                            }

                            return Yii::$app->user->can('other-document-delete') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? $form->field($otherDocumentModels[$index], "[$index]id")->checkbox([
                                'value' => $index,
                                'class' => 'check-other-document-item', 
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
            <?= Yii::$app->user->can('other-document-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-other-document-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#other-document-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-other-document-button").attr("disabled", false) : $("#delete-selected-other-document-button").attr("disabled", true);
    }

    $(".check-other-document-items").change(function() {
        $(".check-other-document-item").prop("checked", $(this).prop("checked"));
        $("#other-document-table tr").toggleClass("isChecked", $(".check-other-document-item").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-other-document-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#other-document-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#other-document-table tr").each(function() {
            var checkbox = $(this).find(".check-other-document-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-other-document-item").removeAttr("checked");
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
.check-other-document-item {
  pointer-events: none !important;
}

#other-document-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#other-document-table > thead > tr > td,
#other-document-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>