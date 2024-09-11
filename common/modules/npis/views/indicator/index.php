<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\lspSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Indicators';
$this->params['breadcrumbs'][] = 'Competencies';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="indicator-index">
    <?= $this->render('_search', [
        'model' => $searchModel,
        'competencies' => $competencies,
    ]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Indicator Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('competency-indicator-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>
            <br>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'competency-indicator-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'competency-indicator-table'
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
                        'value' => function($model){
                            return $model->competency->competency;
                        }
                    ],
                    [
                        'attribute' => 'proficiency',
                        'header' => 'PROFICIENCY LEVEL',
                        'headerOptions' => [
                            'style' => 'width: 32%;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ]
                    ],
                    [
                        'attribute' => 'indicator',
                        'header' => 'INDICATOR',
                        'format' => 'raw',
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
                                return Yii::$app->user->can('competency-indicator-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-competency-indicator-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model) use ($form, $indicatorModels){
                            return Yii::$app->user->can('competency-indicator-delete') ? $form->field($indicatorModels[$model->id], "[$model->id]id")->checkbox([
                                'value' => $model->id,
                                'class' => 'check-competency-indicator-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ],
            ]); ?>

        <div class="form-group pull-right"> 
            <?= Yii::$app->user->can('competency-indicator-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-competency-indicator-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#competency-indicator-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-competency-indicator-button").attr("disabled", false) : $("#delete-selected-competency-indicator-button").attr("disabled", true);
    }

    $(".check-competency-indicator-items").change(function() {
        $(".check-competency-indicator-item").prop("checked", $(this).prop("checked"));
        $("#competency-indicator-table tr").toggleClass("isChecked", $(".check-competency-indicator-item").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-competency-indicator-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#competency-indicator-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#competency-indicator-table tr").each(function() {
            var checkbox = $(this).find(".check-competency-indicator-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-competency-indicator-item").removeAttr("checked");
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
.check-competency-indicator-item {
  pointer-events: none !important;
}

#competency-indicator-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#competency-indicator-table > thead > tr > td,
#competency-indicator-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>