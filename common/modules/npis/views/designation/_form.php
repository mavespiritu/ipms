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
/* @var $model common\modules\npis\models\CareerPath */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="designation-form">

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
                'data' => \common\models\Employee::getAllActiveList(),
                'options' => [
                    'multiple' => false, 
                    'placeholder' => 'Select one', 
                    'class' => 'employee-select',
                ],
                'pluginOptions' => [
                    'allowClear' =>  false,
                ],
                'pluginEvents'=>[
                    'select2:select'=>'
                        function(){
                            $.ajax({
                                url: "'.Url::to(['/npis/designation/position-list']).'",
                                data: {
                                        id: this.value,
                                    }
                            }).done(function(result) {
                                $(".position-select").html("").select2({ data:result, theme:"krajee", width:"100%",placeholder:"Select one", allowClear: true});
                                $(".position-select").select2("val","");
                            });
                        }'
    
                ]
            ])->label('Name of Staff')
    ?>

    <?= $form->field($model, 'position_id')->widget(Select2::classname(), [
        'data' => $positions,
        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'position-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ])->label('Position');
    ?>

    <?= $form->field($model, 'start_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
        'pluginEvents' => [
            'changeDate' => "function(e) {
                const dateReceived = $('#careerpath-start_date');
                const dateActed = $('#careerpath-end_date-kvdate');
                dateActed.val('');
                dateActed.kvDatepicker('update', '');
                dateActed.kvDatepicker('setStartDate', dateReceived.val());
            }",
        ]
    ])->label("Start Date"); ?>
    
    <?= $form->field($model, 'end_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ])->label("End Date"); ?>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="pull-right">
                <?= Html::a('Cancel', ['/npis/designation/'], ['class' => 'btn btn-default']) ?>
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
