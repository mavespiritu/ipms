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
/* @var $model common\modules\npis\models\training */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="evidence-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($evidenceModel, 'isTraining')->widget(Select2::classname(), [
            'data' => ['0' => 'No', '1' => 'Yes'],
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'onchange' => 'selectEvidenceForm(this.value, '.$indicator->id.')', 'class'=>'position-select'],
            'pluginOptions' => [
                'allowClear' =>  false,
            ],
        ])
    ?>

    <?php ActiveForm::end(); ?>

    <div id="evidence-form"></div>

</div>

<?php
    $script = '
        function selectEvidenceForm(id, indicator_id)
        {
            if(id == 0){
                $.ajax({
                    url: "'.Url::to(['/npis/cga/evidence-form']).'?id=" + id + "&indicator_id=" + indicator_id,
                    beforeSend: function(){
                        $("#evidence-form").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                    },
                    success: function (data) {
                        console.log(this.data);
                        $("#evidence-form").empty();
                        $("#evidence-form").hide();
                        $("#evidence-form").fadeIn("slow");
                        $("#evidence-form").html(data);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            }else{
                $.ajax({
                    url: "'.Url::to(['/npis/cga/training-form']).'?id=" + id + "&indicator_id=" + indicator_id,
                    beforeSend: function(){
                        $("#evidence-form").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                    },
                    success: function (data) {
                        console.log(this.data);
                        $("#evidence-form").empty();
                        $("#evidence-form").hide();
                        $("#evidence-form").fadeIn("slow");
                        $("#evidence-form").html(data);
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
