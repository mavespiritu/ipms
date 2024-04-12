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
/* @var $model common\modules\npis\models\Employeerecognition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recognition-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'recognition-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-9',
            ],
        ],
    ]); ?>

    <?= $form->field($recognitionModel, 'description')->textInput(['maxlength' => true])->label('Non-academic distinction/recognition') ?>

    <?= $form->field($recognitionModel, 'internal_external')->widget(Select2::classname(), [
        'data' => [
            'Internal' => 'Internal',
            'External' => 'External',
        ],
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'internal_external-select','id' => $idx.'-internal_external-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        'pluginEvents'=>[
            'select2:select'=>'
                function(){
                    $.ajax({
                        url: "'.Url::to(['/npis/pds/scope-list']).'",
                        data: {
                                type: this.value,
                            }
                    }).done(function(result) {
                        $(".type_detail-select").html("").select2({ data:result, theme:"krajee", width:"100%",placeholder:"Select One", allowClear: true});
                        $(".type_detail-select").select2("val","");
                    });
                }'

        ]
        ]);
    ?>

    <?= $form->field($recognitionModel, 'type_detail')->widget(Select2::classname(), [
        'data' => $scopes,
        'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class' => 'type_detail-select','id' => $idx.'-type_detail-select'],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
        ]);
    ?>

    <?= $form->field($recognitionModel, 'year')->textInput(['maxlength' => true, 'type' => 'number']) ?>

    <?= $form->field($recognitionModel, 'award_giver')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="attachment">Attachment</label>
        <div class="col-sm-9"
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <?= AttachmentsInput::widget([
                        'id' => $idx.'-recognition-attachment-select',
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
                <?= Html::submitButton('Save Record', ['class' => 'btn btn-success', 'onclick' => '$("#'.$idx.'-recognition-attachment-select").fileinput("upload");']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $script = '
    $("#recognition-form").on("beforeSubmit", function(e) {
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
                viewRecognitions("'.$model->emp_id.'");

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