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

<div class="evidence-others-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'evidence-others-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <small>
        <div class="table-responsive">
            <table class="table table-condensed table-responsive">
                <tbody>
                    <tr>
                        <td>Competency:</td>
                        <td><b><?= $indicator->competency->competency ?></b></td>
                        <td>Proficiency Level:</td>
                        <td><b><?= $indicator->proficiency ?></b></td>
                        <td>Indicator:</td>
                        <td><b><?= $indicator->indicator ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </small>

    <?= $form->field($evidenceModel, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($evidenceModel, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 3, 'id' => 'evidence-others-description-'.$idx.'-'.$tab],
        'preset' => 'basic'
    ]) ?>

    <?= $form->field($evidenceModel, 'start_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off', 'id' => $idx.'-'.$tab.'-evidence-from_date'],
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

    <?= $form->field($evidenceModel, 'end_date')->widget(DatePicker::className(), [
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off', 'id' => $idx.'-'.$tab.'-evidence-end_date'],
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
                        'id' => $idx.'-'.$tab.'-evidence-others-attachment-select',
                        'model' => $evidenceModel,
                        'options' => [ 
                            'multiple' => true,
                        ],
                        'pluginOptions' => [ 
                            'showPreview' => false,
                            'showUpload' => false,
                            'maxFileCount' => 5,
                        ]
                    ]) ?>
                    <p style="text-align: right">Allowed file types: jpg, png, pdf (max 10MB each)</p>
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="pull-right">
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success', 'onclick' => "$('#".$idx."-evidence-others-attachment-select').fileinput('upload');"]) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $script = '
    $("#evidence-others-form").on("beforeSubmit", function(e) {
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
                viewEvidences('.$indicator->id.', "'.$model->emp_id.'", "'.$tab.'");
                if("'.$action.'" == "create"){
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