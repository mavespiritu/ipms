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

<div class="evidence-training-select-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'training-select-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($evidenceTrainingModel, 'seminar_title')->widget(Select2::classname(), [
            'data' => $trainings,
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'training-select'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
        ])->label('Select training')
    ?>

    <?= $form->field($evidenceModel, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 3, 'id' => 'evidence-training-select-description-'.$idx],
        'preset' => 'basic'
    ]) ?>

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
    $("#training-select-form").on("beforeSubmit", function(e) {
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
                viewEvidences('.$indicator->id.', "'.$model->emp_id.'");
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