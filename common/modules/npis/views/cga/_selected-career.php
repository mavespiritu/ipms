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
<span class="pull-right">
    <a href="javascript:void(0)" onclick="deleteCareer(<?= $model->id ?>)"><u>Remove this position</u></a>
</span>
<span class="clearfix"></span>
<?php
    $script = '
    function deleteCareer(id){
        var con = confirm("Are you sure you want to remove this position in your career path?");

        if(con){
            $.ajax({
                url: "'.Url::to(['/npis/cga/delete-career']).'?id=" + id,
                type: "POST",
                data: {id: id},
                success: function (data) {
                    viewMyCareerPath("'.$model->emp_id.'");
                },
                error: function (err) {
                    console.log(err);
                }
            });
        } 
    }
    ';

    $this->registerJs($script, View::POS_END);
?>