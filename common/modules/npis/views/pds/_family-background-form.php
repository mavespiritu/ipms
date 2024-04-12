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

<div class="family-background-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'family-background-form'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <p style="font-size: 16px">Spouse's Information</p>
    
    <?= $form->field($model, 'spouse_surname')->textInput(['maxlength' => true])->label('Surname') ?>

    <?= $form->field($model, 'spouse_firstname')->textInput(['maxlength' => true])->label('First Name') ?>

    <?= $form->field($model, 'spouse_middlename')->textInput(['maxlength' => true])->label('Middle Name') ?>

    <?= $form->field($spouseOccupationModel, 'occupation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($spouseOccupationModel, 'employer_business_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($spouseOccupationModel, 'business_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($spouseOccupationModel, 'tel_no')->textInput(['maxlength' => true]) ?>

    <p style="font-size: 16px">Father's Information</p>

    <?= $form->field($model, 'father_surname')->textInput(['maxlength' => true])->label('Surname') ?>

    <?= $form->field($model, 'father_firstname')->textInput(['maxlength' => true])->label('First Name') ?>

    <?= $form->field($model, 'father_middlename')->textInput(['maxlength' => true])->label('Middle Name') ?>
    
    <?= $form->field($model, 'father_birthday')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ])->label('Date of Birth'); ?>

    <p style="font-size: 16px">Mother's Information</p>

    <?= $form->field($model, 'mother_maiden_name')->textInput(['maxlength' => true])->label('Mother\'s Maiden Name') ?>

    <?= $form->field($model, 'mother_surname')->textInput(['maxlength' => true])->label('Surname') ?>

    <?= $form->field($model, 'mother_firstname')->textInput(['maxlength' => true])->label('First Name') ?>

    <?= $form->field($model, 'mother_middlename')->textInput(['maxlength' => true])->label('Middle Name') ?>

    <?= $form->field($model, 'mother_birthday')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ])->label('Date of Birth'); ?>

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
    $("#family-background-form").on("beforeSubmit", function(e) {
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
                viewFamilyBackground("'.$model->emp_id.'");

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