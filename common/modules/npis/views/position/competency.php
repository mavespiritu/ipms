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

<div style="height: 100%;">
    <?php if(empty($availableDescriptors)){ ?>
        <p class="text-center">No included compentency found.</p>
    <?php } ?>
    <br>
    <div style="height: calc(100vh - 300px); overflow-y: auto; padding: 10px;">
        <?php if(!empty($availableDescriptors)){ ?>
            <?php foreach($availableDescriptors as $type => $competencies){ ?>
                <h5><?= strtoupper($type) ?></h5>
                <?= !empty($competencies) ? Collapse::widget(['items' => $competencies, 'encodeLabels' => false, 'autoCloseItems' => true]) : '' ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<?php
    $script = '
        function viewSelectedCompetency(position_id, competency_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/position/view-selected-competency']).'?position_id=" + position_id + "&competency_id=" + competency_id,
                beforeSend: function(){
                    $("#selected-competency-"+competency_id+"-information").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#selected-competency-"+competency_id+"-information").empty();
                    $("#selected-competency-"+competency_id+"-information").hide();
                    $("#selected-competency-"+competency_id+"-information").fadeIn("slow");
                    $("#selected-competency-"+competency_id+"-information").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>