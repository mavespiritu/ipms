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

<div class="position-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'item_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'position_id')->widget(Select2::classname(), [
            'data' => $positions,
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'position-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])
    ?>

    <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
            'data' => $divisions,
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'division-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])
    ?>

    <?= $form->field($model, 'grade')->textInput(['type' => 'number', 'maxlength' => true]) ?>

    <?= $form->field($model, 'step')->textInput(['type' => 'number', 'maxlength' => true]) ?>

    <?= $form->field($model, 'status')->widget(Select2::classname(), [
        'data' => ['1' => 'Active', '0' => 'Inactive'],
        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'status-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
    ])
    ?>

    <?= $form->field($model, 'coterminus')->widget(Select2::classname(), [
        'data' => ['1' => 'Yes', '0' => 'No'],
        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'coterminus-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
    ])
    ?>

    <?= $form->field($model, 'type')->widget(Select2::classname(), [
        'data' => ['A' => 'Administrative', 'T' => 'Technical', 'ST', 'Support to Technical'],
        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'type-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
    ])
    ?>

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
