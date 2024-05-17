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
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="evidence-training-form">
    
    <div class="text-center">
        <a href="javascript:void(0)" id="select-training-button" onClick="viewSelectTrainingForm(<?= $indicator->id ?>, '<?= $reference ?>', '<?= $model->emp_id ?>')" class="menu-link">Select training</a>
        <a href="javascript:void(0)" id="new-training-button" onClick="viewNewTrainingForm(<?= $indicator->id ?>, '<?= $reference ?>', '<?= $model->emp_id ?>')" class="menu-link">Add new training</a>
    </div>
    <br>
    <div id="evidence-training-form" style="min-height: 500px;">
        <div class="flex-center" style="height: 500px;">
            <h4 style="color: gray;">Select action above to add evidence.</h4>
        </div>
    </div>

</div>
<style>
  .menu-link {
    display: inline-block; /* Makes the links block-level for padding */
    padding: 5px 20px; /* Adjust padding as needed */
    margin: 0 5px 5px 5px; /* Adjust margin as needed */
    text-decoration: none; /* Remove underline */
    color: #000; /* Default text color */
    background-color: #EFF1F3; /* Default background color */
    border-radius: 5px; /* Optional: add rounded corners */
    border: 1px solid #E2E8F0;
    transition: background-color 0.3s, color 0.3s; /* Smooth transitions */
  }

  .menu-link:hover {
    color: #000; /* Same as the default text color */
    }

  .menu-link.active {
    background-color: #175676; /* Active background color */
    color: white; /* Active text color */
  }
</style>

<?php
    $script = '
        function viewSelectTrainingForm(id, reference, emp_id)
        {
            $("#select-training-button").addClass("active");
            $("#new-training-button").removeClass("active");

            $.ajax({
                url: "'.Url::to(['/npis/cga/select-training']).'?id=" + id + "&reference=" + reference + "&emp_id=" + emp_id,
                beforeSend: function(){
                    $("#evidence-training-form").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#evidence-training-form").empty();
                    $("#evidence-training-form").hide();
                    $("#evidence-training-form").fadeIn("slow");
                    $("#evidence-training-form").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewNewTrainingForm(id, reference, emp_id)
        {
            $("#new-training-button").addClass("active");
            $("#select-training-button").removeClass("active");

            $.ajax({
                url: "'.Url::to(['/npis/cga/new-training']).'?id=" + id + "&reference=" + reference + "&emp_id=" + emp_id,
                beforeSend: function(){
                    $("#evidence-training-form").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#evidence-training-form").empty();
                    $("#evidence-training-form").hide();
                    $("#evidence-training-form").fadeIn("slow");
                    $("#evidence-training-form").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>