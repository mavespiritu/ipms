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
                'id' => 'update-education-modal-'.($idx + 1),
                'size' => 'modal-lg',
                'header' => '<div id="update-education-modal-'.($idx + 1).'-header"><h4>Edit Record</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-education-modal-'.($idx + 1).'-content"></div>';
            Modal::end();
        }    
    } 
?>

<div>
    <h4>Educational Background
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 1 of 4</span>
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
        <?= Html::button('Add Record', ['value' => Url::to(['/npis/pds/create-education', 'id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'create-education-button']) ?>
    </div>
    <div class="clearfix"></div>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'education-check-form'],
    ]); ?>

    <?php Pjax::begin([
        'id' => 'education-grid-pjax', 
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
            'id' => 'education-table'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
            ],
            [
                'attribute' => 'level',
                'header' => 'LEVEL',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'school',
                'header' => 'NAME OF SCHOOL',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
            ],
            [
                'attribute' => 'course',
                'header' => 'BASIC EDUCATION/<br>DEGREE/<br>COURSE',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'from_date',
                'header' => 'FROM',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'to_date',
                'header' => 'TO',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'highest_attainment',
                'header' => 'HIGHEST<br>LEVEL/UNITS EARNED<br>(if not graduated)',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'year_graduated',
                'header' => 'YEAR<br>GRADUATED',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'awards',
                'header' => 'SCHOLARSHIP/<br>ACADEMIC<br>HONORS<br>RECEIVED',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'filename',
                'header' => 'ATTACHMENT',
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model){
                    return $model->filePath;
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

                    if(Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N)){
                        return Html::a('<i class="fa fa-pencil"></i>', '#', [
                            'class' => 'update-education-button',
                            'data-toggle' => 'modal',
                            'data-target' => '#update-education-modal-'.$index,
                            'data-url' => Url::to(['/npis/pds/update-education', 'emp_id' => $model->emp_id, 'level' => $model->level, 'course' => $model->course, 'school' => $model->school, 'from_date' => $model->from_date, 'idx' => $index]),
                        ]);
                    }else{
                        return $model->approval == 'no' ? Html::a('<i class="fa fa-pencil"></i>', '#', [
                            'class' => 'update-education-button',
                            'data-toggle' => 'modal',
                            'data-target' => '#update-education-modal-'.$index,
                            'data-url' => Url::to(['/npis/pds/update-education', 'emp_id' => $model->emp_id, 'level' => $model->level, 'course' => $model->course, 'school' => $model->school, 'from_date' => $model->from_date, 'idx' => $index]),
                        ]) : '';
                    }
                }
            ],
            [
                'header' => '<input type=checkbox name="items" class="check-education-items" />',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider, $form, $educationModels){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    if(Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N)){
                        return $form->field($educationModels[$index], "[$index]id")->checkbox([
                            'value' => $index,
                            'class' => 'check-education-item', 
                            'label' => ''
                        ]);
                    }else{
                        return $model->approval == 'no' ? $form->field($educationModels[$index], "[$index]id")->checkbox([
                            'value' => $index,
                            'class' => 'check-education-item', 
                            'label' => ''
                        ]) : '';
                    }
                }
            ],
        ],
    ]); ?>

    <?php
        $this->registerJs('
            $(document).ready(function(){
                $(".check-education-item").removeAttr("checked");
                enableRemoveButton();

                function initModal(modalId, contentUrl) {
                    $(modalId).modal("show").find(modalId + "-content").load(contentUrl);
                }

                function toggleBoldStyle() {
                    $("#education-table tr").removeClass("bold-style"); // Remove bold style from all rows
                    $("#education-table tr").each(function() {
                        var checkbox = $(this).find(".check-education-item");
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
                    $("#education-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-education-button").attr("disabled", false) : $("#delete-selected-education-button").attr("disabled", true);
                    $("#education-check-form input:checkbox:checked").length > 0 ? $("#approve-selected-education-button").attr("disabled", false) : $("#approve-selected-education-button").attr("disabled", true);
                }

                $(".update-education-button").click(function(e){
                e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".check-education-items").change(function() {
                    $(".check-education-item").prop("checked", $(this).prop("checked"));
                    var inp = $(this).find(".check-education-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                    
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });
    
                $("tr").click(function() {
                    var inp = $(this).find(".check-education-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                 
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });

                $(document).on("pjax:success", function() {
                    
                    $(".check-education-item").removeAttr("checked");

                    if (!$("#education-grid-pjax").data("first-load")) {
                        return;
                    }
                    $(".update-education-button").each(function() {
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Reinitialize modal and content
                        return false;
                    });
                    // Mark that the first load has completed
                    $("#education-grid-pjax").data("first-load", false);

                    $(".check-education-items").change(function() {
                        $(".check-education-item").prop("checked", $(this).prop("checked"));
                        var inp = $(this).find(".check-education-item");
                        var tr = $(this).closest("tr");
                        inp.prop("checked", !inp.is(":checked"));
                     
                        tr.toggleClass("isChecked", inp.is(":checked"));
                        toggleBoldStyle();
                        enableRemoveButton();
                    });
        
                    $("tr").click(function() {
                        var inp = $(this).find(".check-education-item");
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

    <?php $targetValue = 'no' ?>
    <?php $unapproved = array_count_values(array_column($dataProvider->getModels(), 'approval')) ?>
    <?php $unapprovedCount = $unapproved[$targetValue] ?? 0; ?>

    <div class="form-group pull-left">
    <?= Yii::$app->user->can('Staff') && ($model->emp_id == Yii::$app->user->identity->userinfo->EMP_N) && $unapprovedCount >= 1 ? Html::button('Send Entries for Approval', ['class' => 'btn bg-navy', 'id' => 'notify-education-button',]) : '' ?>
    </div>
    <div class="form-group pull-right"> 
        <?= Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? Html::button('Approve Selected', ['class' => 'btn btn-success', 'id' => 'approve-selected-education-button', 'disabled' => true]) : '' ?>
        <?php if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){ ?>
            <?= Yii::$app->user->can('HR') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-education-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected education information?'], 'disabled' => true]) : '' ?>
        <?php }else{ ?> 
            <?= Yii::$app->user->can('Staff') && $unapprovedCount >= 1 ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-education-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected education information?'], 'disabled' => true]) : '' ?>
        <?php } ?>
    </div>
    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
<?php
    Modal::begin([
        'id' => 'create-education-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-education-modal-header"><h4>Add Record</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-education-modal-content"></div>';
    Modal::end();
?>
<?php
    $script = '
    function enableRemoveButton()
    {
        $("#education-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-education-button").attr("disabled", false) : $("#delete-selected-education-button").attr("disabled", true);
        $("#education-check-form input:checkbox:checked").length > 0 ? $("#approve-selected-education-button").attr("disabled", false) : $("#approve-selected-education-button").attr("disabled", true);
    }

    $("#education-check-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        var indexes = [];
        $("#education-table tbody tr.isChecked").each(function() {
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

    $("#approve-selected-education-button").on("click", function (e) {
        e.preventDefault();

        var con = confirm("Are you sure you want to approve selected education information?");

        if(con){
            var form = $("#education-check-form");
            var formData = form.serialize();
            
            $.ajax({
            type: "POST",
            url: "'.Url::to(['/npis/pds/approve-education', 'id' => $model->emp_id]).'",
            data: formData,
            success: function (data) {
                viewEducationalBackground("'.$model->emp_id.'");
            },
            error: function (err) {
                console.log(err);
            }
            }); 
        }     

        return false;
    });

    $("#notify-education-button").on("click", function (e) {
        e.preventDefault();

        var con = confirm("Are you sure you want to notify HR to approve your entries in educational background?");

        if(con){
            var form = $("#education-check-form");
            var formData = form.serialize();
            
            $.ajax({
            type: "POST",
            url: "'.Url::to(['/npis/pds/notify', 'id' => $model->emp_id, 'content' => 'Education']).'",
            data: formData,
            success: function (data) {
                viewEducationalBackground("'.$model->emp_id.'");
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
            $("#create-education-button").click(function(){
                $("#create-education-modal").modal("show").find("#create-education-modal-content").load($(this).attr("value"));
            });

            $(".check-education-item").removeAttr("checked");
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
.check-education-item {
  pointer-events: none !important;
}

#education-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#education-table > thead > tr > td,
#education-table > thead > tr > th
{
    border: 2px solid white;
}
</style>