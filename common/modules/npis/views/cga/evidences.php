<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\web\View;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
?>
<?php 
    $cloneDataProvider = clone $dataProvider;
    $cloneDataProvider->pagination = false;
    if($cloneDataProvider->models){
        foreach($cloneDataProvider->models as $idx => $model){

            Modal::begin([
                'id' => 'update-evidence-modal-'.($idx + 1),
                'size' => 'modal-lg',
                'header' => '<div id="update-evidence-modal-'.($idx + 1).'-header"><h4>Edit Record</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-evidence-modal-'.($idx + 1).'-content"></div>';
            Modal::end();
        }    
    } 
?>
<div class="evidences-information">
    <br>
    <div class="pull-right">
        <?= Html::button('Add Record', ['value' => Url::to(['/npis/cga/create-evidence', 'id' => $indicator->id]), 'class' => 'btn btn-success btn-sm', 'id' => 'create-evidence-button']) ?>
    </div>
    <div class="clearfix"></div>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'evidence-check-form'],
    ]); ?>

    <?php Pjax::begin([
        'id' => 'evidence-grid-pjax', 
        'enablePushState' => false, 
        'enableReplaceState' => false,
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'table-responsive'
        ],
        'tableOptions' => [
            'class' => 'table table-bordered table-condensed table-hover',
            'id' => 'evidence-table'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'start_date',
                'header' => 'DATE',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important; width: 20%;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model){
                    return $model->start_date != $model->end_date ? date("F j, Y", strtotime($model->start_date)).' - '.date("F j, Y", strtotime($model->end_date)) : date("F j, Y", strtotime($model->start_date));
                }
            ],
            [
                'attribute' => 'justification',
                'header' => 'JUSTIFICATION',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
            ],
            [
                'attribute' => 'attachment',
                'header' => 'ATTACHMENT',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model){
                    $file = '';
                    if($model->files){ 
                        foreach($model->files as $file){ 
                            $file .= Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id]);
                        }
                    }
                    return $file;
                }
            ],
            [
                'attribute' => 'approver',
                'header' => 'VERIFIED BY',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important; width: 10%;'
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
                        'class' => 'update-evidence-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-evidence-modal-'.$index,
                        'data-url' => Url::to(['/npis/cga/update-evidence', 'id' => $model->id]),
                    ]);
                }
            ],
            [
                'header' => '<input type=checkbox name="items" class="check-evidence-items" />',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider, $form, $evidenceModels){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    return $form->field($evidenceModels[$index], "[$index]id")->checkbox([
                        'value' => $index,
                        'class' => 'check-evidence-item', 
                        'label' => ''
                    ]);
                }
            ],
        ],
    ]); ?>

    <?php
        $this->registerJs('
            $(document).ready(function(){
                $(".check-evidence-item").removeAttr("checked");
                enableRemoveButton();

                function initModal(modalId, contentUrl) {
                    $(modalId).modal("show").find(modalId + "-content").load(contentUrl);
                }

                function toggleBoldStyle() {
                    $("#evidence-table tr").removeClass("bold-style"); // Remove bold style from all rows
                    $("#evidence-table tr").each(function() {
                        var checkbox = $(this).find(".check-evidence-item");
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
                    $("#evidence-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-evidence-button").attr("disabled", false) : $("#delete-selected-evidence-button").attr("disabled", true);
                    $("#evidence-check-form input:checkbox:checked").length > 0 ? $("#approve-selected-evidence-button").attr("disabled", false) : $("#approve-selected-evidence-button").attr("disabled", true);
                }

                $(".update-evidence-button").click(function(e){
                e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".check-evidence-items").change(function() {
                    $(".check-evidence-item").prop("checked", $(this).prop("checked"));
                    var inp = $(this).find(".check-evidence-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                    
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });
    
                $("tr").click(function() {
                    var inp = $(this).find(".check-evidence-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                 
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });

                $(document).on("pjax:success", function() {
                    
                    $(".check-evidence-item").removeAttr("checked");

                    if (!$("#evidence-grid-pjax").data("first-load")) {
                        return;
                    }
                    $(".update-evidence-button").each(function() {
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Reinitialize modal and content
                        return false;
                    });
                    // Mark that the first load has completed
                    $("#evidence-grid-pjax").data("first-load", false);

                    $(".check-evidence-items").change(function() {
                        $(".check-evidence-item").prop("checked", $(this).prop("checked"));
                        var inp = $(this).find(".check-evidence-item");
                        var tr = $(this).closest("tr");
                        inp.prop("checked", !inp.is(":checked"));
                     
                        tr.toggleClass("isChecked", inp.is(":checked"));
                        toggleBoldStyle();
                        enableRemoveButton();
                    });
        
                    $("tr").click(function() {
                        var inp = $(this).find(".check-evidence-item");
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
        <?php //Html::button('Approve Selected', ['class' => 'btn btn-success btn-sm', 'id' => 'approve-selected-evidence-button', 'disabled' => true]) ?>

        <?= Html::submitButton('Delete Selected', ['class' => 'btn btn-danger btn-sm', 'id' => 'delete-selected-evidence-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected evidence information?'], 'disabled' => true]) ?>
    </div>
    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
<?php
    Modal::begin([
        'id' => 'create-evidence-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-evidence-modal-header"><h4>Add Record</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-evidence-modal-content"></div>';
    Modal::end();
?>
<?php
    $script = '
    function enableRemoveButton()
    {
        $("#evidence-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-evidence-button").attr("disabled", false) : $("#delete-selected-evidence-button").attr("disabled", true);
        $("#evidence-check-form input:checkbox:checked").length > 0 ? $("#approve-selected-evidence-button").attr("disabled", false) : $("#approve-selected-evidence-button").attr("disabled", true);
    }

    $("#evidence-check-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        var indexes = [];
        $("#evidence-table tbody tr.isChecked").each(function() {
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
                $("#education-table tbody").find(selector).remove();
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

    $("#approve-selected-evidence-button").on("click", function (e) {
        e.preventDefault();

        var con = confirm("Are you sure you want to approve selected evidence information?");

        if(con){
            var form = $("#evidence-check-form");
            var formData = form.serialize();
            
            $.ajax({
            type: "POST",
            url: "'.Url::to(['/npis/pds/approve-evidence', 'id' => $indicator->id]).'",
            data: formData,
            success: function (data) {
                viewEvidences('.$indicator->id.');
            },
            error: function (err) {
                console.log(err);
            }
            }); 
        }     

        return false;
    });

    $("#notify-evidence-button").on("click", function (e) {
        e.preventDefault();

        var con = confirm("Are you sure you want to notify your DC or the HR to review and approve your entries in this competency indicator?");

        if(con){
            var form = $("#evidence-check-form");
            var formData = form.serialize();
            
            $.ajax({
            type: "POST",
            url: "'.Url::to(['/npis/cga/notify', 'id' => $indicator->id]).'",
            data: formData,
            success: function (data) {
                viewEvidences('.$indicator->id.');
            },
            error: function (err) {
                console.log(err);
            }
            }); 
        }     

        return false;
    });
    ';

    $this->registerJs($script, View::POS_END);
?>

<?php
    $script = '
        $(document).ready(function(){
            $("#create-evidence-button").click(function(){
                $("#create-evidence-modal").modal("show").find("#create-evidence-modal-content").load($(this).attr("value"));
            });

            $(".check-evidence-item").removeAttr("checked");
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
/* click-through element */
.check-evidence-item {
  pointer-events: none !important;
}

#evidence-table > tbody > tr{
    background-color: white;
}

#evidence-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#evidence-table > thead > tr > td,
#evidence-table > thead > tr > th
{
    border: 2px solid white;
}
</style>