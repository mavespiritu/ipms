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
use yii\bootstrap\Modal;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
/* @var $form yii\widgets\ActiveForm */
?>
<small>
    <table class="table table-condensed table-responsive">
        <tr>
            <td>Item No.:</td>
            <td><b><?= $model->position_id ?></b></td>
            <td>Position:</td>
            <td><b><?= $model->employeePositionItem->position_id ?></b></td>
        </tr>
        <tr>
            <td>Division:</td>
            <td><b><?= $model->employeePositionItem->division_id ?></b></td>
            <td>SG and Step:</td>
            <td><b><?= $model->employeePositionItem->grade.'-'.$model->employeePositionItem->step ?></b></td>
        </tr>
        <tr>
            <td>Start Date:</td>
            <td><b><?= date("F j, Y", strtotime($model->start_date)) ?></b></td>
            <td>End Date:</td>
            <td><b><?= !is_null($model->end_date) ? date("F j, Y", strtotime($model->end_date)) : '' ?></b></td>
        </tr>
    </table>
</small>

<span class="clearfix"></span>
