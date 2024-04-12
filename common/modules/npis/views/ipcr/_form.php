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

<div class="ipcr-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'emp_id')->widget(Select2::classname(), [
            'data' => \common\models\Employee::getAllExceptSelfList(),
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'employee-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])->label('Name of Staff')
    ?>

    <?= $form->field($model, 'year')->textInput(['type' => 'number', 'maxlength' => true]) ?>

    <?= $form->field($model, 'semester')->widget(Select2::classname(), [
            'data' => [ 1 => '1st Semester', 2 => '2nd Semester'],
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'semester-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])
    ?>

    <?= $form->field($model, 'rating')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="attachment">Attachment</label>
        <div class="col-sm-9"
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <?= empty($model->files) ? AttachmentsInput::widget([
                        'id' => 'file-input', // Optional
                        'model' => $model,
                        'options' => [ 
                            'multiple' => false, 
                            //'required' => 'required'
                        ],
                        'pluginOptions' => [ 
                            'showPreview' => false,
                            'showUpload' => false,
                            'maxFileCount' => 1,
                        ]
                    ]) : AttachmentsInput::widget([
                        'id' => 'file-input', // Optional
                        'model' => $model,
                        'options' => [ 
                            'multiple' => false
                        ],
                        'pluginOptions' => [ 
                            'showPreview' => false,
                            'showUpload' => false,
                            'maxFileCount' => 1,
                        ]
                    ])  ?>
                    <p style="text-align: right">Allowed file types: jpg, png, pdf (max 10MB each)</p>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="pull-right">
                <?= Html::a('Cancel', ['/npis/ipcr/'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
