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
                'id' => 'update-skill-modal-'.($idx + 1),
                'size' => 'modal-lg',
                'header' => '<div id="update-skill-modal-'.($idx + 1).'-header"><h4>Edit Record</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-skill-modal-'.($idx + 1).'-content"></div>';
            Modal::end();
        }    
    } 
?>

<div>
    <h4>Special Skills and Hobbies
    </h4>

    <div id="skill-alert" class="alert" role="alert" style="display: none;"></div>
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
        <?= Html::button('Add Record', ['value' => Url::to(['/npis/pds/create-skill', 'id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'create-skill-button']) ?>
    </div>
    <div class="clearfix"></div>
    
    <br>

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'skill-check-form'],
    ]); ?>

    <?php Pjax::begin([
        'id' => 'skill-grid-pjax', 
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
            'id' => 'skill-table'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 50px;'
                ],
            ],
            [
                'attribute' => 'description',
                'header' => 'SPECIAL SKILLS AND HOBBIES',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
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
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'headerOptions' => [
                    'style' => 'width: 50px;'
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
                            'class' => 'update-skill-button',
                            'data-toggle' => 'modal',
                            'data-target' => '#update-skill-modal-'.$index,
                            'data-url' => Url::to(['/npis/pds/update-skill', 'emp_id' => $model->emp_id, 'type' => $model->type, 'description' => $model->description, 'idx' => $index]),
                        ]);
                    }else{
                        return $model->approval == 'no' ? Html::a('<i class="fa fa-pencil"></i>', '#', [
                            'class' => 'update-skill-button',
                            'data-toggle' => 'modal',
                            'data-target' => '#update-skill-modal-'.$index,
                            'data-url' => Url::to(['/npis/pds/update-skill', 'emp_id' => $model->emp_id, 'type' => $model->type, 'description' => $model->description, 'idx' => $index]),
                        ]) : '';
                    }
                }
            ],
            [
                'header' => '<input type=checkbox name="items" class="check-skill-items" />',
                'headerOptions' => [
                    'style' => 'width: 50px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider, $form, $skillModels){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    if(Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N)){
                        return $form->field($skillModels[$index], "[$index]id")->checkbox([
                            'value' => $index,
                            'class' => 'check-skill-item', 
                            'label' => ''
                        ]);
                    }else{
                        return $model->approval == 'no' ? $form->field($skillModels[$index], "[$index]id")->checkbox([
                            'value' => $index,
                            'class' => 'check-skill-item', 
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
                $(".check-skill-item").removeAttr("checked");
                enableRemoveButton();

                function initModal(modalId, contentUrl) {
                    $(modalId).modal("show").find(modalId + "-content").load(contentUrl);
                }

                function toggleBoldStyle() {
                    $("#skill-table tr").removeClass("bold-style"); // Remove bold style from all rows
                    $("#skill-table tr").each(function() {
                        var checkbox = $(this).find(".check-skill-item");
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
                    $("#skill-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-skill-button").attr("disabled", false) : $("#delete-selected-skill-button").attr("disabled", true);
                    $("#skill-check-form input:checkbox:checked").length > 0 ? $("#approve-selected-skill-button").attr("disabled", false) : $("#approve-selected-skill-button").attr("disabled", true);
                }

                $(".update-skill-button").click(function(e){
                e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".check-skill-items").change(function() {
                    $(".check-skill-item").prop("checked", $(this).prop("checked"));
                    var inp = $(this).find(".check-skill-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                    
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });
    
                $("tr").click(function() {
                    var inp = $(this).find(".check-skill-item");
                    var tr = $(this).closest("tr");
                    inp.prop("checked", !inp.is(":checked"));
                 
                    tr.toggleClass("isChecked", inp.is(":checked"));
                    toggleBoldStyle();
                    enableRemoveButton();
                });

                $(document).on("pjax:success", function() {
                    
                    $(".check-skill-item").removeAttr("checked");

                    if (!$("#skill-grid-pjax").data("first-load")) {
                        return;
                    }
                    $(".update-skill-button").each(function() {
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Reinitialize modal and content
                        return false;
                    });
                    // Mark that the first load has completed
                    $("#skill-grid-pjax").data("first-load", false);

                    $(".check-skill-items").change(function() {
                        $(".check-skill-item").prop("checked", $(this).prop("checked"));
                        var inp = $(this).find(".check-skill-item");
                        var tr = $(this).closest("tr");
                        inp.prop("checked", !inp.is(":checked"));
                     
                        tr.toggleClass("isChecked", inp.is(":checked"));
                        toggleBoldStyle();
                        enableRemoveButton();
                    });
        
                    $("tr").click(function() {
                        var inp = $(this).find(".check-skill-item");
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
        <?= Yii::$app->user->can('Staff') && ($model->emp_id == Yii::$app->user->identity->userinfo->EMP_N) && $unapprovedCount >= 1 ? Html::button('Send Entries for Approval', ['class' => 'btn bg-navy', 'id' => 'notify-skill-button',]) : '' ?>
    </div>

    <div class="form-group pull-right"> 
        <?= Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? Html::button('Approve Selected', ['class' => 'btn btn-success', 'id' => 'approve-selected-skill-button', 'disabled' => true]) : '' ?>
        <?php if($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N){ ?>
            <?= Yii::$app->user->can('HR') ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-skill-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected skills?'], 'disabled' => true]) : '' ?>
        <?php }else{ ?> 
            <?= Yii::$app->user->can('Staff') && $unapprovedCount >= 1 ? Html::submitButton('Delete Selected', ['class' => 'btn btn-danger', 'id' => 'delete-selected-skill-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to delete selected skills?'], 'disabled' => true]) : '' ?>
        <?php } ?>
    </div>
    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>
</div>
<?php
    Modal::begin([
        'id' => 'create-skill-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-skill-modal-header"><h4>Add Record</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-skill-modal-content"></div>';
    Modal::end();
?>
<?php
    $script = '
    function enableRemoveButton()
    {
        $("#skill-check-form input:checkbox:checked").length > 0 ? $("#delete-selected-skill-button").attr("disabled", false) : $("#delete-selected-skill-button").attr("disabled", true);
        $("#skill-check-form input:checkbox:checked").length > 0 ? $("#approve-selected-skill-button").attr("disabled", false) : $("#approve-selected-skill-button").attr("disabled", true);
    }

    $("#skill-check-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        var indexes = [];
        $("#skill-table tbody tr.isChecked").each(function() {
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
                $("#skill-table tbody").find(selector).remove();
                enableRemoveButton();
                $("#skill-alert").removeClass("alert-danger").addClass("alert-success").html(data.success).show();
                setTimeout(function(){
                    $("#skill-alert").fadeOut("slow");
                }, 3000);
            },
            error: function (err) {
                console.log(err);
                $("#skill-alert").removeClass("alert-success").addClass("alert-danger").html("Error occurred while processing the request.").show();
                setTimeout(function(){
                    $("#skill-alert").fadeOut("slow");
                }, 3000);
            }
        }); 
        
        return false;
    });

    $("#approve-selected-skill-button").on("click", function (e) {
        e.preventDefault();

        var con = confirm("Are you sure you want to approve selected skill?");

        if(con){
            var form = $("#skill-check-form");
            var formData = form.serialize();
            
            $.ajax({
            type: "POST",
            url: "'.Url::to(['/npis/pds/approve-skill', 'id' => $model->emp_id]).'",
            data: formData,
            success: function (data) {
                viewSkills("'.$model->emp_id.'");
            },
            error: function (err) {
                console.log(err);
            }
            }); 
        }     

        return false;
    });

    $("#notify-skill-button").on("click", function (e) {
        e.preventDefault();

        var con = confirm("Are you sure you want to notify HR to approve your entries in training?");

        if(con){
            var form = $("#skill-check-form");
            var formData = form.serialize();
            
            $.ajax({
            type: "POST",
            url: "'.Url::to(['/npis/pds/notify', 'id' => $model->emp_id, 'content' => 'Skill']).'",
            data: formData,
            success: function (data) {
                viewSkills("'.$model->emp_id.'");
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
            $("#create-skill-button").click(function(){
                $("#create-skill-modal").modal("show").find("#create-skill-modal-content").load($(this).attr("value"));
            });

            $(".check-skill-item").removeAttr("checked");
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
.check-skill-item {
  pointer-events: none !important;
}

#skill-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#skill-table > thead > tr > td,
#skill-table > thead > tr > th
{
    border: 2px solid white;
}
</style>