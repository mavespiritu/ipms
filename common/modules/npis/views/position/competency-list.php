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

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'options' => ['id' => 'competency-check-form'],
]); ?>

<div style="height: 100%;">
    <h5><?= $model->competency ?><br>
    <small>List of Indicators</small></h5>
    <?php if(!empty($availableDescriptors)){ ?>
        <p class="text-right" style="margin-right: 5px;">Select All&nbsp;&nbsp;&nbsp;<input type="checkbox" name="items" class="check-competency-items" /></p>
    <?php }else{ ?>
        <br>
        <p class="text-center">You have already included all indicators in this competency.</p>
    <?php } ?>
    <div style="height: calc(100vh - 465px); overflow-y: auto; padding: 10px;">
        <?php if(!empty($availableDescriptors)){ ?>
            <?php foreach($availableDescriptors as $proficiency => $descriptors){ ?>
                <table class="table table-responsive table-condensed table-bordered table-hover comp-table" id="competency-<?= $proficiency ?>-table">
                    <thead>
                        <tr>
                            <th colspan=2 align=center><b>LEVEL <?= $proficiency ?></b></th>
                            <td><input type="checkbox" name="items" class="check-competency-<?= $proficiency ?>-items" /></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($descriptors)){ ?>
                        <?php foreach($descriptors as $i => $descriptor){ ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= $descriptor->indicator ?></td>
                                <td align=center>
                                    <?= $form->field($descriptorModels[$descriptor->id], "[$descriptor->id]id")->checkbox([
                                        'value' => $descriptor->id,
                                        'class' => 'check-competency-'.$proficiency.'-item', 
                                        'style' => 'pointer-events: none !important;',
                                        'label' => ''
                                    ], false) ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>

                <?php
                $this->registerJs('
                    $(document).ready(function(){
                        $(".check-competency-'.$proficiency.'-item").removeAttr("checked");
                        enableAddButton();

                        $(".check-competency-items").change(function() {
                            var isChecked = $(this).prop("checked");
                            $(".check-competency-'.$proficiency.'-items").prop("checked", isChecked);
                            $(".check-competency-'.$proficiency.'-item").prop("checked", isChecked);
                            $("#competency-'.$proficiency.'-table tr").toggleClass("isChecked", isChecked);
                            enableAddButton();
                        });

                        $(".check-competency-'.$proficiency.'-items").change(function() {
                            var isChecked = $(this).prop("checked");
                            $(".check-competency-'.$proficiency.'-item").prop("checked", isChecked);
                            $("#competency-'.$proficiency.'-table tr").toggleClass("isChecked", isChecked);
                            enableAddButton();
                        });

                        $(".check-competency-'.$proficiency.'-item").change(function() {
                            var isChecked = $(this).prop("checked");
                            $(this).closest("tr").toggleClass("isChecked", isChecked);
                            enableAddButton();
                        });

                        $("#competency-'.$proficiency.'-table tr").click(function() {
                            var checkbox = $(this).find(".check-competency-'.$proficiency.'-item");
                            checkbox.prop("checked", !checkbox.prop("checked"));
                            $(this).toggleClass("isChecked", checkbox.prop("checked"));
                            enableAddButton();
                        });
                    });
                    ');
                ?>
            <?php } ?>
        <?php } ?>
    </div>
    <br>
    <div class="pull-right">
        <?= !empty($availableDescriptors) ? Html::submitButton('Add Selected Indicators', ['class' => 'btn btn-success', 'id' => 'add-selected-competency-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to add selected competency indicators?'], 'disabled' => true]) : '' ?>
    </div>
    <div class="clearfix"></div>
</div>

<?php ActiveForm::end(); ?>
<style>
.isChecked {
  background-color: #F5F5F5 !important;
  font-weight: bolder !important;
}

tr{
  background-color: white;
}

.comp-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}
.comp-table > thead > tr > td,
.comp-table > thead > tr > th
{
    border: 2px solid white;
    font-weight: bolder;
}
</style>

<?php
    $script = '
    function enableAddButton()
    {
        $("#competency-check-form input:checkbox:checked").length > 0 ? $("#add-selected-competency-button").attr("disabled", false) : $("#add-selected-competency-button").attr("disabled", true);
    }

    $("#competency-check-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                enableAddButton();
                selectCompetency('.$model->comp_id.',"'.$position->item_no.'");
                viewCompetency("'.$position->item_no.'");
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