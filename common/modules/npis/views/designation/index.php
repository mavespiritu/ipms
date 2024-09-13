<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\DesignationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Designations';
$this->params['breadcrumbs'][] = 'Competencies';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="designation-index">

    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Designation Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('designation-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>

            <br>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'designation-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'designation-table'
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
                        'header' => 'NAME OF STAFF',
                        'format' => 'raw',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'value' => function($model){
                            return $model->employee->name;
                        }
                    ],
                    [
                        'header' => 'POSITION',
                        'format' => 'raw',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            return $model->employeePositionItem->position_id;
                        }
                    ],
                    [
                        'header' => 'DIVISION',
                        'format' => 'raw',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            return $model->employeePositionItem->division_id;
                        }
                    ],
                    [
                        'header' => 'START DATE',
                        'format' => 'raw',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            return !is_null($model->start_date) ? date("F j, Y", strtotime($model->start_date)): '';
                        }
                    ],
                    [
                        'header' => 'END DATE',
                        'format' => 'raw',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            return !is_null($model->end_date) ? date("F j, Y", strtotime($model->end_date)): '';
                        }
                    ],
                    [
                        'header' => 'ATTACHMENT',
                        'format' => 'raw',
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
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '&nbsp;',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'template' => '<center>{update}</center>',
                        'buttons' => [
                            'update' => function($url, $model, $key){
                                return Yii::$app->user->can('designation-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-designation-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model) use ($form, $designationModels){
                            return Yii::$app->user->can('designation-delete') ? $form->field($designationModels[$model->id], "[$model->id]id")->checkbox([
                                'value' => $model->id,
                                'class' => 'check-designation-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ]
            ]); ?>

            <div class="form-group pull-right"> 
                <?= Yii::$app->user->can('designation-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-designation-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#designation-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-designation-button").attr("disabled", false) : $("#delete-selected-designation-button").attr("disabled", true);
    }

    $(".check-designation-items").change(function() {
        $(".check-designation-item").prop("checked", $(this).prop("checked"));
        $("#designation-table tr").toggleClass("isChecked", $(".check-included-project").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-designation-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#designation-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#designation-table tr").each(function() {
            var checkbox = $(this).find(".check-designation-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-designation-item").removeAttr("checked");
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
.check-designation-item {
  pointer-events: none !important;
}

#designation-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#designation-table > thead > tr > td,
#designation-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>