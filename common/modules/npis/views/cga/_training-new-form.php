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

<div class="evidence-training-new-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'training-new-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($trainingModel, 'seminar_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($trainingModel, 'sponsor')->textInput(['maxlength' => true]) ?>

    <?= $form->field($trainingModel, 'discipline')->widget(Select2::classname(), [
        'data' => $disciplines,
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'discipline-select','id' => $idx.'-discipline-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ]);
    ?>

    <?= $form->field($trainingModel, 'category')->widget(Select2::classname(), [
        'data' => $categories,
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'category-select','id' => $idx.'-category-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ]);
    ?>

    <?= $form->field($trainingModel, 'from_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off', 'id' => $idx.'-employeetraining-from_date'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
        /* 'pluginEvents' => [
            'changeDate' => "function(e) {
                const dateReceived = $('#employeetraining-from_date');
                const dateActed = $('#employeetraining-to_date-kvdate');
                dateActed.val('');
                dateActed.kvDatepicker('update', '');
                dateActed.kvDatepicker('setStartDate', dateReceived.val());
            }",
        ] */
    ]); ?>

    <?= $form->field($trainingModel, 'to_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off', 'id' => $idx.'-employeetraining-to_date'],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ],
    ]); ?>

    <?= $form->field($trainingModel, 'hours')->textInput(['maxlength' => true, 'type' => 'number']) ?>

    <?= $form->field($trainingModel, 'participation')->widget(Select2::classname(), [
        'data' => [
            'Facilitator' => 'Facilitator',
            'Speaker' => 'Speaker',
            'Trainee' => 'Trainee',
        ],
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'participation-select','id' => $idx.'-participation-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ]);
    ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="attachment">Attachment</label>
        <div class="col-sm-9"
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <?= AttachmentsInput::widget([
                        'id' => $idx.'-evidence-training-attachment-select',
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
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success', 'onclick' => "$('#".$idx."-evidence-training-attachment-select').fileinput('upload');"]) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $script = '
    $("#training-new-form").on("beforeSubmit", function(e) {
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
                viewEvidences('.$indicator->id.');
                if('.$action.' == "create"){
                    $("#evidence-badge-'.$indicator->id.'").html(parseInt($("#evidence-badge-'.$indicator->id.'").html()) + 1);
                }
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