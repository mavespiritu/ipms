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
use dosamigos\ckeditor\CKEditor;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\training */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="job-description-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'item_no')->textInput(['value' => $model->item_no, 'disabled' => true]) ?>

    <?= $form->field($model, 'reports_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'classification')->widget(Select2::classname(), [
        'data' => [
            'Executive' => 'Executive',
            'Middle Management' => 'Middle Management',
            'Professional & Supervisory & Technical' => 'Professional & Supervisory & Technical',
            'Clerical & General Staff' => 'Clerical & General Staff',
        ],
        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'classification-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
    ])
    ?>

    <h5 style="margin-left: 15%;"><b>A. Qualification Guide</b></h5>
    <br>

    <h5 style="margin-left: 16%;"><b>CSC-Prescribed QS</b></h5>
    <br>

    <?= $form->field($model, 'prescribed_eligibility')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'prescribed_education')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'prescribed_experience')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'prescribed_training')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <h5 style="margin-left: 14%;"><b>Preferred Qualifications</b></h5>
    <br>

    <?= $form->field($model, 'preferred_eligibility')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'preferred_education')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'preferred_experience')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'preferred_training')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'examination')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <br>

    <?= $form->field($model, 'summary')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ])->label('B. Job Summary') ?>

    <?= $form->field($model, 'output')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ])->label('C. Job Output') ?>

    <?= $form->field($model, 'responsibility')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ])->label('D. Duties and Responsibilities') ?>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="pull-right">
                <?= Html::a('Cancel', ['/npis/position/'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
