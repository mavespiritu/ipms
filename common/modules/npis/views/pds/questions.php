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

$this->title = 'My PDS Related Files';
$this->params['breadcrumbs'][] = $this->title;

$options = ['true' => 'YES', 'false' => 'NO'];
\yii\web\YiiAsset::register($this);
?>
<div class="questions-view">
    <h4>Questions
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 4 of 4</span>
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

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'questions-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
    ]); ?>

    <table class="table table-bordered question-table">
        <tr>
            <th>34. <?= $questions['q1']['question'] ?></th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">a. <?= $questions['q1']['A'] ?></th>
            <td>
                <?= $form->field($questionModels['q1A'], '[q1A]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q1A'], '[q1A]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">b. <?= $questions['q1']['B'] ?></th>
            <td>
                <?= $form->field($questionModels['q1B'], '[q1B]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q1B'], '[q1B]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th>35.</th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">a. <?= $questions['q2']['A'] ?></th>
            <td>
                <?= $form->field($questionModels['q2A'], '[q2A]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q2A'], '[q2A]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">b. <?= $questions['q2']['B'] ?></th>
            <td>
                <?= $form->field($questionModels['q2B'], '[q2B]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q2B'], '[q2B]yes_details')->textInput(['maxlength' => true])->label('If YES, give details (date filed, status of case/s):'); ?>
            </td>
        </tr>
        <tr>
            <th>36. <?= $questions['q3']['question'] ?></th>
            <td>
                <?= $form->field($questionModels['q3'], '[q3]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q3'], '[q3]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th>37. <?= $questions['q4']['question'] ?></th>
            <td>
                <?= $form->field($questionModels['q4'], '[q4]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q4'], '[q4]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th>38.</th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">a. <?= $questions['q5']['A'] ?></th>
            <td>
                <?= $form->field($questionModels['q5A'], '[q5A]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q5A'], '[q5A]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">b. <?= $questions['q5']['B'] ?></th>
            <td>
                <?= $form->field($questionModels['q5B'], '[q5B]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q5B'], '[q5B]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th>39. <?= $questions['q6']['question'] ?></th>
            <td>
                <?= $form->field($questionModels['q6'], '[q6]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q6'], '[q6]yes_details')->textInput(['maxlength' => true])->label('If YES, give details (country):'); ?>
            </td>
        </tr>
        <tr>
            <th>40. <?= $questions['q7']['question'] ?></th>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">a. <?= $questions['q7']['A'] ?></th>
            <td>
                <?= $form->field($questionModels['q7A'], '[q7A]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q7A'], '[q7A]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">b. <?= $questions['q7']['B'] ?></th>
            <td>
                <?= $form->field($questionModels['q7B'], '[q7B]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q7B'], '[q7B]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
        <tr>
            <th style="text-indent: 40px;">c. <?= $questions['q7']['C'] ?></th>
            <td>
                <?= $form->field($questionModels['q7C'], '[q7C]answer')->inline()->radioList($options)->label(false); ?>
                <?= $form->field($questionModels['q7C'], '[q7C]yes_details')->textInput(['maxlength' => true])->label('If YES, give details:'); ?>
            </td>
        </tr>
    </table>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="pull-right">
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    table.question-table th{
        background-color: #F4F4F5;  
        font-weight: normal; 
        border: 1px solid #ECF0F5 !important;
        width: 50% !important;
    }
    table.question-table td{
        border: 1px solid #ECF0F5 !important;
        width: 50% !important;
    }
    label.control-label{
        font-weight: normal !important;
    }
</style>
<?php
    $script = '
    $("#questions-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                $("#alert").removeClass("alert-danger").addClass("alert-success").html(data.success).show();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                setTimeout(function(){
                    $("#alert").fadeOut("slow");
                }, 3000);
            },
            error: function (err) {
                console.log(err);
                $("#alert").removeClass("alert-success").addClass("alert-danger").html("Error occurred while processing the request.").show();
                $("html, body").animate({ scrollTop: 0 }, "slow");
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