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

<div class="eligibility-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'eligibility-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($eligibilityModel, 'eligibility')->textInput(['maxlength' => true]) ?>

    <?= $form->field($eligibilityModel, 'exam_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ]); ?>

    <?= $form->field($eligibilityModel, 'rating')->textInput(['maxlength' => true]) ?>

    <?= $form->field($eligibilityModel, 'exam_place')->textInput(['maxlength' => true]) ?>

    <?= $form->field($eligibilityModel, 'license_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($eligibilityModel, 'release_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off', $idx.'-employeecivilservice-release_date'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ]); ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="attachment">Attachment</label>
        <div class="col-sm-9"
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <?= AttachmentsInput::widget([
                        'id' => $idx.'-eligibility-attachment-select',
                        'model' => $idModel,
                        'options' => [ 
                            'multiple' => false,
                        ],
                        'pluginOptions' => [ 
                            'showPreview' => false,
                            'showUpload' => false,
                            'maxFileCount' => 1,
                        ]
                    ]) ?>
                    <p style="text-align: right">Allowed file types: jpg, png, pdf (max 10MB each)</p>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="pull-right">
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success', 'onclick' => "$('#".$idx."-eligibility-attachment-select').fileinput('upload');"]) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $script = '
    $("#eligibility-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                $(".modal").remove();
                $(".modal-backdrop").remove();
                $("body").removeClass("modal-open");
                $("body").css("padding-right", "");
                viewEligibility("'.$model->emp_id.'");

            },
            error: function (err) {
                console.log(err);
            }
        }); 
        
        return false;
    });
    ';

    $this->registerJs($script, View::POS_END);
?>