<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
?>
<?php 
    if($model->children){
        foreach($model->children as $child){
            $modelID = $child->emp_id.'-'.preg_replace('/[^a-zA-Z]/', '', $child->child_name);
            Modal::begin([
                'id' => 'update-child-modal-'.$modelID,
                'size' => 'modal-md',
                'header' => '<div id="update-child-modal-'.$modelID.'-header"><h4>Edit Child Information</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-child-modal-'.$modelID.'-content"></div>';
            Modal::end();
        }    
    } 
?>

<div>
    <h4>Family Background
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 1 of 4</span>
        <div class="pull-right">
            <?= Html::button('Add Child', ['value' => Url::to(['/npis/pds/create-child', 'id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'create-child-button']) ?>
        </div>
        <div class="clearfix"></div>
    </h4>
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
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <table class="table table-condensed table-responsive table-bordered inverted-table">
                <tr>
                    <th style="width: 40%;">SPOUSE'S SURNAME </th>
                    <td style="width: 60%;"><?= $model->spouse_surname ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">FIRST NAME </th>
                    <td style="width: 60%;"><?= $model->spouse_firstname ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">MIDDLE NAME </th>
                    <td style="width: 60%;"><?= $model->spouse_middlename ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">OCCUPATION </th>
                    <td style="width: 60%;"><?= $model->spouseOccupation ? $model->spouseOccupation->occupation : '-' ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">EMPLOYER/BUSINESS NAME </th>
                    <td style="width: 60%;"><?= $model->spouseOccupation ? $model->spouseOccupation->employer_business_name : '-' ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">BUSINESS ADDRESS </th>
                    <td style="width: 60%;"><?= $model->spouseOccupation ? $model->spouseOccupation->business_address : '-' ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">TELEPHONE NO. </th>
                    <td style="width: 60%;"><?= $model->spouseOccupation ? $model->spouseOccupation->tel_no : '-' ?></td>
                </tr>
            </table>
            <table class="table table-condensed table-responsive table-bordered inverted-table">
                <tr>
                    <th style="width: 40%;">FATHER'S SURNAME </th>
                    <td style="width: 60%;"><?= $model->father_surname ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">FIRST NAME </th>
                    <td style="width: 60%;"><?= $model->father_firstname ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">MIDDLE NAME </th>
                    <td style="width: 60%;"><?= $model->father_middlename ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">DATE OF BIRTH<br>(mm/dd/yyyy) </th>
                    <td style="width: 60%;"><?= date("m/d/Y", strtotime($model->father_birthday)) ?></td>
                </tr>
            </table>
            <table class="table table-condensed table-responsive table-bordered inverted-table">
                <tr>
                    <th style="width: 40%;">MOTHER'S MAIDEN NAME </th>
                    <td style="width: 60%;"><?= $model->mother_maiden_name ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">MOTHER'S SURNAME </th>
                    <td style="width: 60%;"><?= $model->mother_surname ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">FIRST NAME </th>
                    <td style="width: 60%;"><?= $model->mother_firstname ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">MIDDLE NAME </th>
                    <td style="width: 60%;"><?= $model->mother_middlename ?></td>
                </tr>
                <tr>
                    <th style="width: 40%;">DATE OF BIRTH<br>(mm/dd/yyyy) </th>
                    <td style="width: 60%;"><?= date("m/d/Y", strtotime($model->mother_birthday)) ?></td>
                </tr>
            </table>
            <div class="pull-right">
                <?= Html::button('Edit Information', ['value' => Url::to(['/npis/pds/update-family-background', 'id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'family-background-button']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-6 col-xs-12">
            <table class="table table-condensed table-responsive table-bordered">
                <thead>
                    <tr>
                        <th style="text-align: center; font-weight: bolder;">#</th>
                        <th style="text-align: center; font-weight: bolder;">NAME OF CHILDREN (Write full and list all)</th>
                        <th style="text-align: center; font-weight: bolder;">DATE OF BIRTH (mm/dd/yyyy)</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($model->children){ ?>
                    <?php foreach($model->children as $i => $child){ ?>
                        <tr>
                            <td align=center><?= $i+1 ?></td>
                            <td><?= $child->child_name ?></td>
                            <td align=center><?= date("m/d/Y", strtotime($child->birthday)) ?></td>
                            <td align=center>
                                <?= Html::a('<i class="fa fa-pencil"></i>', '#', [
                                    'class' => 'update-child-button',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#update-child-modal-'.$child->emp_id.'-'.preg_replace('/[^a-zA-Z]/', '', $child->child_name),
                                    'data-url' => Url::to(['/npis/pds/update-child', 'emp_id' => $child->emp_id, 'child_name' => $child->child_name]),
                                ]) ?>
                                <?= Html::a('<i class="fa fa-trash"></i>', ['#'], [
                                    'class' => 'delete-child-button',
                                    'data' => [
                                        'confirm' => 'Are you sure want to delete this record?',
                                        'method' => 'post',
                                        'emp-id' => $child->emp_id,
                                        'child-name' => $child->child_name,
                                    ],
                                ]) ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php }else{ ?>
                    <tr>
                        <td colspan=4 align=center style="font-weight: normal !important;">No child found.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
    Modal::begin([
        'id' => 'family-background-modal',
        'size' => "modal-lg",
        'header' => '<div id="family-background-modal-header"><h4>Edit Information</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="family-background-modal-content"></div>';
    Modal::end();
?>
<?php
    Modal::begin([
        'id' => 'create-child-modal',
        'size' => "modal-md",
        'header' => '<div id="create-child-modal-header"><h4>Add Child Information</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-child-modal-content"></div>';
    Modal::end();
?>
<?php
    $script = '
        $(document).ready(function(){
            $("#family-background-button").click(function(){
                $("#family-background-modal").modal("show").find("#family-background-modal-content").load($(this).attr("value"));
            });

            $("#create-child-button").click(function(){
                $("#create-child-modal").modal("show").find("#create-child-modal-content").load($(this).attr("value"));
            });
        });     
    ';

    $this->registerJs($script, View::POS_END);
?>
<?php
$this->registerJs('
    $(".update-child-button").click(function(e){
        e.preventDefault();

        var modalId = $(this).data("target");
        $(modalId).modal("show").find(modalId + "-content").load($(this).data("url"));
        
        return false;
    });
');
?>
<?php
    $script = '
    $(".delete-child-button").on("click", function(e) {
        e.preventDefault();

        var con = confirm("Are you sure want to delete this record?");
        if(con)
        {
            var emp_id = $(this).data("emp-id");
            var child_name = $(this).data("child-name");

            $.ajax({
                url: "'.Url::to(['/npis/pds/delete-child']).'",
                type: "POST",
                data: {emp_id: emp_id, child_name: child_name},
                success: function (data) {
                    viewFamilyBackground("'.$model->emp_id.'");
    
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
$this->registerJs("
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').fadeOut('slow');
        }, 3000);
    });
");
?>