<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\lspSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LSP';
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="lsp-index">
    <?= $this->render('_search', ['model' => $searchModel]) ?>
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">LSP Records</h3></div>
        <div class="box-body">
            <div class="pull-right">
                <?= Yii::$app->user->can('lsp-create') ? Html::a('Add New Record', ['create'], ['class' => 'btn btn-success']) : '' ?>
            </div>
            <div class="clearfix"></div>
            <br>

            <?php $form = ActiveForm::begin([
                'options' => ['id' => 'lsp-check-form'],
            ]); ?>

            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-hover',
                    'id' => 'lsp-table'
                ],
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                    ],
                    [
                        'attribute' => 'lsp_name',
                        'header' => 'NAME OF LSP',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'address',
                        'header' => 'ADDRESS',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'contact_no',
                        'header' => 'CONTACT NO.',
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                    ],
                    [
                        'attribute' => 'specialization',
                        'header' => 'SPECIALIZATION',
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
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '&nbsp;',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'template' => '<center>{update}</center>',
                        'buttons' => [
                            'update' => function($url, $model, $key){
                                return Yii::$app->user->can('lsp-update') ? Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-link'
                                ]) : '';
                            }
                        ],
                    ],
                    [
                        'header' => '<input type=checkbox name="items" class="check-lsp-items" />',
                        'headerOptions' => [
                            'style' => 'width: 40px;'
                        ],
                        'contentOptions' => [
                            'style' => 'text-align: center;'
                        ],
                        'format' => 'raw',
                        'value' => function($model) use ($form, $lspModels){
                            return Yii::$app->user->can('lsp-delete') ? $form->field($lspModels[$model->id], "[$model->id]id")->checkbox([
                                'value' => $model->id,
                                'class' => 'check-lsp-item', 
                                'label' => ''
                            ]) : '';
                        }
                    ],
                ],
            ]); ?>

        <div class="form-group pull-right"> 
            <?= Yii::$app->user->can('lsp-delete') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-lsp-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected items?'], 'disabled' => true]) : '' ?>
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
        $("#lsp-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-lsp-button").attr("disabled", false) : $("#delete-selected-lsp-button").attr("disabled", true);
    }

    $(".check-lsp-items").change(function() {
        $(".check-lsp-item").prop("checked", $(this).prop("checked"));
        $("#lsp-table tr").toggleClass("isChecked", $(".check-included-project").is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    $("tr").click(function() {
        var inp = $(this).find(".check-lsp-item");
        var tr = $(this).closest("tr");
        inp.prop("checked", !inp.is(":checked"));
     
        tr.toggleClass("isChecked", inp.is(":checked"));
        toggleBoldStyle();
        enableRemoveButton();
    });

    function toggleBoldStyle() {
        $("#lsp-table tr").removeClass("bold-style"); // Remove bold style from all rows
        $("#lsp-table tr").each(function() {
            var checkbox = $(this).find(".check-lsp-item");
            if (checkbox.length > 0 && checkbox.is(":checked")) {
                $(this).addClass("bold-style");
                $(this).addClass("isChecked");
            }
        });
        enableRemoveButton();
    }

    $(document).ready(function(){
        $(".check-lsp-item").removeAttr("checked");
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
.check-lsp-item {
  pointer-events: none !important;
}

#lsp-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#lsp-table > thead > tr > td,
#lsp-table > thead > tr > th
{
    border: 2px solid white;
}
</style>