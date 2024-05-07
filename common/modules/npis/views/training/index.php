<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
use common\modules\npis\models\Competency;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\trainingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trainings';
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="training-index">
    <?= $this->render('_search', [
        'model' => $searchModel,
        'lsps' => $lsps
    ]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Training Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('training-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>

            <br>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'training-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'training-table'
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
                        'header' => 'NAME OF LSP',
                        'format' => 'raw',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'value' => function($model){
                            return $model->learningServiceProvider->lsp_name;
                        }
                    ],
                    [
                        'attribute' => 'training_title',
                        'header' => 'TITLE OF TRAINING',
                    ],
                    [
                        'attribute' => 'no_of_hours',
                        'header' => 'NO. OF HOURS',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'modality',
                        'header' => 'MODALITY',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'header' => 'COST',
                        'format' => 'raw',
                        'contentOptions' => [
                            'style' => 'text-align: right;'
                        ],
                        'value' => function($model){
                            return number_format($model->cost, 2);
                        }
                    ],
                    [
                        'attribute' => 'competencies',
                        'header' => 'COMPETENCIES',
                        'headerOptions' => [
                            'style' => 'width: 20%;'
                        ],
                        'format' => 'raw',
                        'value' => function($model){
                            $competencies = ArrayHelper::map($model->competencies, 'competency_id', 'competency_id');
                            $competencies = Competency::find()->select(['competency'])->where(['comp_id' => $competencies])->asArray()->all();
                            $competencies = ArrayHelper::map($competencies, 'competency', 'competency');
                            return !empty($competencies) ? implode(", ", $competencies) : '';
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
                                return Yii::$app->user->can('training-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-training-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model) use ($form, $trainingModels){
                            return Yii::$app->user->can('training-delete') ? $form->field($trainingModels[$model->id], "[$model->id]id")->checkbox([
                                'value' => $model->id,
                                'class' => 'check-training-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ]
            ]); ?>

        <div class="form-group pull-right"> 
            <?= Yii::$app->user->can('training-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-training-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#training-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-training-button").attr("disabled", false) : $("#delete-selected-training-button").attr("disabled", true);
    }

    $(".check-training-items").change(function() {
        $(".check-training-item").prop("checked", $(this).prop("checked"));
        $("#training-table tr").toggleClass("isChecked", $(".check-included-project").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-training-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#training-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#training-table tr").each(function() {
            var checkbox = $(this).find(".check-training-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-training-item").removeAttr("checked");
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
.check-training-item {
  pointer-events: none !important;
}

#training-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#training-table > thead > tr > td,
#training-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>