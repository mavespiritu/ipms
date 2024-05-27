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
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
/* @var $form yii\widgets\ActiveForm */
?>

<ul type="none" class="position-menu" id="position-menu">
    <?php if($positions){ ?>
        <?php foreach($positions as $position){ ?>
            <li onclick="viewPositionCompetencies('<?= $model->emp_id ?>','<?= $position->position_id ?>'); setActive(this);">
                <span style="font-weight: bolder;"><a href="javascript:void(0)"><?= $position->employeePositionItem->position->post_description ?></a></span><br>
                <small style="color: rgb(153, 153, 153);">
                <?= $position->employeePositionItem->division_id ?>
                <span class="pull-right">SG: <?= $position->employeePositionItem->grade.'-'.$position->employeePositionItem->step ?></span>
                </small>
            </li>
        <?php } ?>
    <?php } ?>
</ul>

<style>
    .position-menu{
        line-height: 20px; 
        margin-left: 0 !important;
        padding-left: 0 !important;
    }

    .position-menu li{
        padding: 10px;
        cursor: pointer;
        color: black;
        margin-left: 0 !important;
    }   

    .position-menu li.active{
        border-radius: 0.25rem;
        background-color: #EEF2FF;
        color: #6366F2;
    }

    .position-menu li:hover{
        border-radius: 0.25rem;
        background-color: #EEF2FF;
        color: #6366F2;
    }   
</style>

<?php
    $script = "
        function setActive(element) {
            // Remove the 'active' class from all list items
            var items = document.querySelectorAll('#position-menu li');
            items.forEach(function(item) {
                item.classList.remove('active');
            });
        
            // Add the 'active' class to the clicked list item
            element.classList.add('active');
        }
    ";

    $this->registerJs($script, View::POS_END);
?>