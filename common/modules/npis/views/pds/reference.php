<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\grid\GridView;
use frontend\assets\AppAsset;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
$asset = AppAsset::register($this);

?>
<?php 
    $cloneDataProvider = clone $dataProvider;
    $cloneDataProvider->pagination = false;
    if($cloneDataProvider->models){
        foreach($cloneDataProvider->models as $idx => $model){

            Modal::begin([
                'id' => 'update-reference-modal-'.($idx + 1),
                'size' => 'modal-lg',
                'header' => '<div id="update-reference-modal-'.($idx + 1).'-header"><h4>Edit Record</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-reference-modal-'.($idx + 1).'-content"></div>';
            Modal::end();
        }    
    } 
?>

<div>
    <h4>References
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 4 of 4</span>
    </h4>

    <div id="alert" class="alert" role="alert" style="display: none;"></div>
    <?php
        if(Yii::$app->session->hasFlash('success')):?>
            <div class="alert alert-success" role="alert">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif;
        if(Yii::$app->session->hasFlash('error')):?>
            <div class="alert alert-danger" role="alert">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif;
    ?>

    <div class="pull-right">
        <?= Html::button('Add Record', ['value' => Url::to(['/npis/pds/create-reference', 'id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'create-reference-button']) ?>
    </div>
    <div class="clearfix"></div>
    
    <br>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'reference-check-form'],
    ]); ?>

    <?php Pjax::begin([
        'id' => 'reference-grid-pjax', 
        'enablePushState' => false, 
        'enableReplaceState' => false,
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'table-responsive'
        ],
        'tableOptions' => [
            'class' => 'table table-bordered table-hover',
            'id' => 'reference-table'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
            ],
            [
                'attribute' => 'ref_name',
                'header' => 'NAME',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'address',
                'header' => 'ADDRESS',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'tel_no',
                'header' => 'TELEPHONE/MOBILE NO.',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important; text-align: center'
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

                    return Html::a('<i class="fa fa-pencil"></i>', '#', [
                        'class' => 'update-reference-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-reference-modal-'.$index,
                        'data-url' => Url::to(['/npis/pds/update-reference', 'emp_id' => $model->emp_id, 'ref_name' => $model->ref_name, 'idx' => $index]),
                    ]);
                }
            ],
            [
                'header' => '<input type=checkbox name="items" class="check-reference-items" />',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider, $form, $referenceModels){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    return $form->field($referenceModels[$index], "[$index]id")->checkbox([
                        'value' => $index,
                        'class' => 'check-reference-item', 
                        'label' => ''
                    ]);
                }
            ],
        ],
    ]); ?>

    <?php
        $this->registerJs('
            $(document).ready(function(){
                $(".check-reference-item").removeAttr("checked");
                enableRemoveButton();

                function initModal(modalId, contentUrl) {
                    $(modalId).modal("show").find(modalId + "-content").load(contentUrl);
                }

                function toggleBoldStyle() {
                    $("#reference-table tr").removeClass("bold-style"); // Remove bold style from all rows
                    $("#reference-table tr").each(function() {
                        var checkbox = $(this).find(".check-reference-item");
                        if (checkbox.length > 0) {
                            if(checkbox.is(":checked")){
                                $(this).addClass("isChecked");
                                $(this).addClass("bold-style");
                            }else{
                                $(this).removeClass("isChecked");
                                $(this).removeClass("bold-style");
                            }
                        }
                    });
                }

                function enableRemoveButton()
                {
                    $("#reference-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-reference-button").attr("disabled", false) : $("#delete-selected-reference-button").attr("disabled", true);
                }

                $(".update-reference-button").click(function(e){
                e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".check-reference-items").change(function() {
                    $(".check-reference-item").prop("checked", $(this).prop("checked"));
                    var inp = $(this).find(".check-reference-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                    
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });
    
                $("tr").click(function() {
                    var inp = $(this).find(".check-reference-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                 
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });

                $(document).on("pjax:success", function() {
                    
                    $(".check-reference-item").removeAttr("checked");

                    if (!$("#reference-grid-pjax").data("first-load")) {
                        return;
                    }
                    $(".update-reference-button").each(function() {
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Reinitialize modal and content
                        return false;
                    });
                    // Mark that the first load has completed
                    $("#reference-grid-pjax").data("first-load", false);

                    $(".check-reference-items").change(function() {
                        $(".check-reference-item").prop("checked", $(this).prop("checked"));
                        var inp = $(this).find(".check-reference-item");
                        var tr = $(this).closest("tr");
                        inp.prop("checked", !inp.is(":checked"));
                     
                        tr.toggleClass("isChecked", inp.is(":checked"));
                        toggleBoldStyle();
                        enableRemoveButton();
                    });
        
                    $("tr").click(function() {
                        var inp = $(this).find(".check-reference-item");
                        var tr = $(this).closest("tr");
                        inp.prop("checked", !inp.is(":checked"));
                     
                        tr.toggleClass("isChecked", inp.is(":checked"));
                        toggleBoldStyle();
                        enableRemoveButton();
                    });
                });
            });
        ');
    ?>

    <?php Pjax::end(); ?>

    <div class="form-group pull-right"> 
        <?= Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-reference-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected references?'], 'disabled' => true]) ?>
    </div>
    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
<?php
    Modal::begin([
        'id' => 'create-reference-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-reference-modal-header"><h4>Add Record</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-reference-modal-content"></div>';
    Modal::end();
?>
<?php
    $script = '
    function enableRemoveButton()
    {
        $("#reference-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-reference-button").attr("disabled", false) : $("#delete-selected-reference-button").attr("disabled", true);
    }

    $("#reference-check-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        var indexes = [];
        $("#reference-table tbody tr.isChecked").each(function() {
            indexes.push($(this).index()); // Push the index of each selected row
        });

        var selector = indexes.map(function(value) {
            return "tr:eq(" + value + ")";
        }).join(",");

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                $("#reference-table tbody").find(selector).remove();
                enableRemoveButton();
                $("#alert").removeClass("alert-danger").addClass("alert-success").html(data.success).show();
                setTimeout(function(){
                    $("#alert").fadeOut("slow");
                }, 3000);
            },
            error: function (err) {
                console.log(err);
                $("#alert").removeClass("alert-success").addClass("alert-danger").html("Error occurred while processing the request.").show();
                setTimeout(function(){
                    $("#alert").fadeOut("slow");
                }, 3000);
            }
        }); 
        
        return false;
    });
    ';

    $this->registerJs($script, View::POS_END);
?>

<?php
    $script = '
        $(document).ready(function(){
            $("#create-reference-button").click(function(){
                $("#create-reference-modal").modal("show").find("#create-reference-modal-content").load($(this).attr("value"));
            });

            $(".check-reference-item").removeAttr("checked");
        });     
    ';

    $this->registerJs($script, View::POS_END);
?>
<?php
$this->registerJs("
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').fadeOut('slow');
        }, 3000);
    });
");
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
.check-reference-item {
  pointer-events: none !important;
}

#reference-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#reference-table > thead > tr > td,
#reference-table > thead > tr > th
{
    border: 2px solid white;
}
</style>