<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\lspSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Competencies';
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="competency-index">
    <?= $this->render('_search', [
        'model' => $searchModel,
        'competencyTypes' => $competencyTypes,
    ]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Competency Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('competency-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>
            <br>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'competency-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'competency-table'
                ],
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                    ],
                    [
                        'attribute' => 'competency',
                        'header' => 'COMPETENCY',
                        'headerOptions' => [
                            'style' => 'width: 32%;'
                        ],
                    ],
                    [
                        'attribute' => 'comp_type',
                        'header' => 'TYPE',
                        'headerOptions' => [
                            'style' => 'width: 32%;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'value' => function($model){
                            return $model->compType->competency_type_description;
                        }
                    ],
                    [
                        'attribute' => 'description',
                        'header' => 'DESCRIPTION',
                        'headerOptions' => [
                            'style' => 'width: 32%;'
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
                                return Yii::$app->user->can('competency-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->comp_id], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-competency-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model) use ($form, $competencyModels){
                            return Yii::$app->user->can('competency-delete') ? $form->field($competencyModels[$model->comp_id], "[$model->comp_id]comp_id")->checkbox([
                                'value' => $model->comp_id,
                                'class' => 'check-competency-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ],
            ]); ?>

        <div class="form-group pull-right"> 
            <?= Yii::$app->user->can('competency-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-competency-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#competency-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-competency-button").attr("disabled", false) : $("#delete-selected-competency-button").attr("disabled", true);
    }

    $(".check-competency-items").change(function() {
        $(".check-competency-item").prop("checked", $(this).prop("checked"));
        $("#competency-table tr").toggleClass("isChecked", $(".check-competency-item").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-competency-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#competency-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#competency-table tr").each(function() {
            var checkbox = $(this).find(".check-competency-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-competency-item").removeAttr("checked");
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
.check-competency-item {
  pointer-events: none !important;
}

#competency-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#competency-table > thead > tr > td,
#competency-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>