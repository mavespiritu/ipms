<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveField;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use yii\web\View;
use yii\widgets\MaskedInput;
use kartik\daterange\DateRangePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use \file\components\AttachmentsInput;
use yii\web\JsExpression;
use buttflatteryormwizard\FormWizard;
use dosamigos\switchery\Switchery;
use yii\bootstrap\Modal;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
/* @var $form yii\widgets\ActiveForm */
?>
<small>
    <table class="table table-condensed table-responsive">
        <tr>
            <td>Item No.:</td>
            <td><b><?= $model->position_id ?></b></td>
            <td>Position:</td>
            <td><b><?= $model->positionItem->position_id ?></b></td>
        </tr>
        <tr>
            <td>Division:</td>
            <td><b><?= $model->positionItem->division_id ?></b></td>
            <td>SG and Step:</td>
            <td><b><?= $model->positionItem->grade.'-'.$model->positionItem->step ?></b></td>
        </tr>
        <tr>
            <td>Effectivity Date:</td>
            <td><b><?= date("F j, Y", strtotime($model->start_date)) ?></b></td>
        </tr>
    </table>
</small>
<span class="pull-right">
    <a href="javascript:void(0)" onclick="deleteDesignation(<?= $model->id ?>)"><u>Remove this designation</u></a>
</span>
<span class="clearfix"></span>
<h4>Available Competencies</h4>
<?= $this->render('_search-competency', [
    'model' => $model,
    'competencies' => $competencies
]) ?>
<br>
<div id="designation-competency-list">
    <div class="flex-center" style="height: calc(100vh - 315px);">
        <h4 style="color: gray;">Select designation at the top to add or view evidences.</h4>
    </div>
</div>
<?php
    $script = '
    function selectDesignationCompetency(id, position_id, emp_id)
    {
        $.ajax({
            url: "'.Url::to(['/npis/cga/select-designation-competency']).'?id=" + id + "&position_id=" + position_id + "&emp_id=" + emp_id,
            beforeSend: function(){
                $("#designation-competency-list").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
            },
            success: function (data) {
                console.log(this.data);
                $("#designation-competency-list").empty();
                $("#designation-competency-list").hide();
                $("#designation-competency-list").fadeIn("slow");
                $("#designation-competency-list").html(data);
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    function deleteDesignation(id){
        var con = confirm("Are you sure you want to remove this designation?");

        if(con){
            $.ajax({
                url: "'.Url::to(['/npis/cga/delete-designation']).'?id=" + id,
                type: "POST",
                data: {id: id},
                success: function (data) {
                    viewMyCurrentDesignation("'.$model->emp_id.'");
                },
                error: function (err) {
                    console.log(err);
                }
            });
        } 
    }
    ';

    $this->registerJs($script, View::POS_END);
?>