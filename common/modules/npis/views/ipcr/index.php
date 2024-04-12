<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\IpcrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'IPCR';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="ipcr-index">
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">IPCR Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('ipcr-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'ipcr-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'ipcr-table'
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
                        'attribute' => 'year',
                        'header' => 'YEAR',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ]
                    ],
                    [
                        'attribute' => 'semester',
                        'header' => 'SEMESTER',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->semester == '1' ? '1st Semester' : '2nd Semester';
                        }
                    ],
                    [
                        'attribute' => 'rating',
                        'header' => 'RATING',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
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
                        'attribute' => 'verified_by',
                        'header' => 'VERIFIED BY',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->verifier->name;
                        }
                    ],
                    [
                        'attribute' => 'date_verified',
                        'header' => 'DATE VERIFIED',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
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
                                return Yii::$app->user->can('ipcr-update') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-ipcr-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model) use ($form, $ipcrModels){
                            return Yii::$app->user->can('ipcr-delete') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? $form->field($ipcrModels[$model->id], "[$model->id]id")->checkbox([
                                'value' => $model->id,
                                'class' => 'check-ipcr-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ] : [
                    [
                        'class' => 'yii\grid\SerialColumn',
                    ],
                    [
                        'attribute' => 'year',
                        'header' => 'YEAR',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ]
                    ],
                    [
                        'attribute' => 'semester',
                        'header' => 'SEMESTER',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->semester == '1' ? '1st Semester' : '2nd Semester';
                        }
                    ],
                    [
                        'attribute' => 'rating',
                        'header' => 'RATING',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
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
                        'attribute' => 'verified_by',
                        'header' => 'VERIFIED BY',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->verifier->name;
                        }
                    ],
                    [
                        'attribute' => 'date_verified',
                        'header' => 'DATE VERIFIED',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                ],
            ]); ?>

        <div class="form-group pull-right"> 
            <?= Yii::$app->user->can('ipcr-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-ipcr-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#ipcr-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-ipcr-button").attr("disabled", false) : $("#delete-selected-ipcr-button").attr("disabled", true);
    }

    $(".check-ipcr-items").change(function() {
        $(".check-ipcr-item").prop("checked", $(this).prop("checked"));
        $("#ipcr-table tr").toggleClass("isChecked", $(".check-included-project").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-ipcr-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#ipcr-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#ipcr-table tr").each(function() {
            var checkbox = $(this).find(".check-ipcr-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-ipcr-item").removeAttr("checked");
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
.check-ipcr-item {
  pointer-events: none !important;
}

#ipcr-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#ipcr-table > thead > tr > td,
#ipcr-table > thead > tr > th
{
    border: 2px solid white;
}
</style>