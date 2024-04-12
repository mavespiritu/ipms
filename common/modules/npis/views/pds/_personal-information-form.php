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

<div class="personal-information-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'personal-information-form'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <p style="font-size: 16px">Basic Information</p>
    
    <?= $form->field($model, 'civil_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_place')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ]); ?>

    <?= $form->field($model, 'gender')->widget(Select2::classname(), [
        'data' => ['Male' => 'Male', 'Female' => 'Female'],
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'gender-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ]);
    ?>

    <?= $form->field($model, 'citizenship')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'blood_type')->widget(Select2::classname(), [
        'data' => [
            'A+' => 'A+', 
            'A-' => 'A-', 
            'B+' => 'B+', 
            'B-' => 'B-', 
            'AB+' => 'AB+', 
            'AB-' => 'AB-', 
            'O+' => 'O+',
            'O-' => 'O-'
        ],
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'blood-type-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ]);
    ?>

    <p style="font-size: 16px">Contact Information</p>

    <?= $form->field($model, 'cell_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'e_mail_add')->textInput(['maxlength' => true]) ?>

    <p style="font-size: 16px">Residential Address</p>

    <?= $form->field($model, 'residential_address')->textInput(['maxlength' => true])->label('Complete Address') ?>

    <?= $form->field($addressModels[0], "[0]type")->hiddenInput(['value' => 'residential'])->label(false) ?>

    <?= $form->field($addressModels[0], "[0]house_no")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[0], "[0]street")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[0], "[0]subdivision")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[0], "[0]barangay")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[0], "[0]city")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[0], "[0]province")->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'residential_zip_code')->textInput(['maxlength' => true])->label('Zip Code') ?>

    <?= $form->field($model, 'residential_tel_no')->textInput(['maxlength' => true])->label('Telephone No.') ?>

    <p style="font-size: 16px">Permanent Address</p>

    <?= $form->field($model, 'permanent_address')->textInput(['maxlength' => true])->label('Complete Address') ?>

    <?= $form->field($addressModels[0], "[1]type")->hiddenInput(['value' => 'permanent'])->label(false) ?>

    <?= $form->field($addressModels[1], "[1]house_no")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[1], "[1]street")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[1], "[1]subdivision")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[1], "[1]barangay")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[1], "[1]city")->textInput(['maxlength' => true]) ?>

    <?= $form->field($addressModels[1], "[1]province")->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'permanent_zip_code')->textInput(['maxlength' => true])->label('Zip Code') ?>

    <?= $form->field($model, 'permanent_tel_no')->textInput(['maxlength' => true])->label('Telephone No.') ?>

    <p style="font-size: 16px">Identification Information</p>

    <?= $form->field($model, 'TIN')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Pag_ibig')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'GSIS')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Philhealth')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'SSS')->textInput(['maxlength' => true]) ?>

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
<?php
    $script = '
    $("#personal-information-form").on("beforeSubmit", function(e) {
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
                viewPersonalInformation("'.$model->emp_id.'");

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