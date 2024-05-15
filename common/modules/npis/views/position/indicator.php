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
    'options' => ['id' => 'selected-competency-'.$competency->comp_id.'-check-form'],
]); ?>

<div style="height: 100%;">
    <p><?= $competency->description ?></p>
    <p class="text-right" style="margin-right: 5px;">Select All&nbsp;&nbsp;&nbsp;<input type="checkbox" name="items" class="check-selected-competency-<?= $competency->comp_id ?>-items" /></p>
    <?php if(!empty($availableDescriptors)){ ?>
        <?php foreach($availableDescriptors as $proficiency => $descriptors){ ?>
            <table class="table table-responsive table-condensed table-bordered table-hover comp-table" id="competency-selected-<?= $competency->comp_id ?>-<?= $proficiency ?>-table">
                <thead>
                    <tr>
                        <th colspan=2 align=center><b>LEVEL <?= $proficiency ?></b></th>
                        <td><input type="checkbox" name="items" class="check-selected-competency-<?= $competency->comp_id ?>-<?= $proficiency ?>-items" /></td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($descriptors)){ ?>
                    <?php foreach($descriptors as $i => $descriptor){ ?>
                        <?php $id = $descriptor['id']; ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= $descriptor['indicator'] ?></td>
                            <td align=center>
                                <?= $form->field($descriptorModels[$id], "[$id]id")->checkbox([
                                    'value' => $id,
                                    'class' => 'check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-item', 
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
                    $(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-item").removeAttr("checked");
                    enableRemoveButton();

                    $(".check-selected-competency-'.$competency->comp_id.'-items").change(function() {
                        var isChecked = $(this).prop("checked");
                        $(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-items").prop("checked", isChecked);
                        $(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-item").prop("checked", isChecked);
                        $("#competency-selected-'.$competency->comp_id.'-'.$proficiency.'-table tr").toggleClass("isChecked", isChecked);
                        enableRemoveButton();
                    });

                    $(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-items").change(function() {
                        var isChecked = $(this).prop("checked");
                        $(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-item").prop("checked", isChecked);
                        $("#competency-selected-'.$competency->comp_id.'-'.$proficiency.'-table tr").toggleClass("isChecked", isChecked);
                        enableRemoveButton();
                    });

                    $(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-item").change(function() {
                        var isChecked = $(this).prop("checked");
                        $(this).closest("tr").toggleClass("isChecked", isChecked);
                        enableRemoveButton();
                    });

                    $("#competency-selected-'.$competency->comp_id.'-'.$proficiency.'-table tr").click(function() {
                        var checkbox = $(this).find(".check-selected-competency-'.$competency->comp_id.'-'.$proficiency.'-item");
                        checkbox.prop("checked", !checkbox.prop("checked"));
                        $(this).toggleClass("isChecked", checkbox.prop("checked"));
                        enableRemoveButton();
                    });
                });
                ');
            ?>
        <?php } ?>
    <?php } ?>
    <br>
    <div class="pull-right">
        <?= !empty($availableDescriptors) ? Html::submitButton('Remove Selected Indicators', ['class' => 'btn btn-danger', 'id' => 'remove-selected-competency-'.$competency->comp_id.'-button', 'data' => ['disabled-text' => 'Please Wait', 'method' => 'post', 'confirm' => 'Are you sure you want to remove selected competency indicators?'], 'disabled' => true]) : '' ?>
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
    function enableRemoveButton()
    {
        $("#selected-competency-'.$competency->comp_id.'-check-form input:checkbox:checked").length > 0 ? $("#remove-selected-competency-'.$competency->comp_id.'-button").attr("disabled", false) : $("#remove-selected-competency-'.$competency->comp_id.'-button").attr("disabled", true);
    }

    $("#selected-competency-'.$competency->comp_id.'-check-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                enableRemoveButton();
                selectCompetency('.$competency->comp_id.',"'.$model->item_no.'");
                viewCompetency("'.$model->item_no.'");
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