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

<div class="training-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($model, 'service_provider_id')->widget(Select2::classname(), [
            'data' => $lsps,
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'lsp-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])->label('Name of LSP')
    ?>

    <?= $form->field($model, 'training_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_of_hours')->textInput(['type' => 'number', 'maxlength' => true]) ?>

    <?= $form->field($model, 'cost')->widget(MaskedInput::classname(), [
                'options' => [
                    'autocomplete' => 'off',
                ],
                'clientOptions' => [
                    'alias' =>  'decimal',
                    'removeMaskOnSubmit' => true,
                    'groupSeparator' => ',',
                    'autoGroup' => true,
                ],
            ])->label('Cost') ?>

    <?= $form->field($model, 'modality')->widget(Select2::classname(), [
            'data' => ['Hybrid' => 'Hybrid', 'F2F' => 'F2F', 'Virtual' => 'Virtual'],
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'modality-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])
    ?>

    <?= $form->field($competencyModel, 'competency_id')->widget(Select2::classname(), [
        'data' => $competencies,
        'options' => ['multiple' => true, 'placeholder' => 'Select one or more', 'class'=>'competency-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ])->label('Competencies');
    ?>

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
                <?= Html::a('Cancel', ['/npis/training/'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
