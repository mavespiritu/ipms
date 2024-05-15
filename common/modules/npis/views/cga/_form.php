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

<div class="evidence-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?= $form->field($evidenceModel, 'reference')->widget(Select2::classname(), [
            'data' => [
                'Training' => 'Training', 
                'Award' => 'Award',
                'Performance' => 'Performance',
                'Others' => 'Others',
            ],
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'onchange' => 'selectEvidenceForm(this.value, '.$indicator->id.')', 'class'=>'reference-select'],
            'pluginOptions' => [
                'allowClear' =>  false,
            ],
        ])
    ?>

    <?php ActiveForm::end(); ?>

</div>
