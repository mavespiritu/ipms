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
<div id="alert" class="alert" role="alert" style="display: none;"></div>
<?php
    if(Yii::$app->session->hasFlash('success')):?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif;
    if(Yii::$app->session->hasFlash('error')):?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif;
?>

<div>
    <?= Yii::$app->user->can('HR') ? Html::button('Add new designation', ['value' => Url::to(['/npis/cga/select-designation', 'emp_id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'select-designation-button']) : '' ?>
</div>
<br>
<?php $form = ActiveForm::begin([
    'options' => ['id' => 'designation-view-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
    //'enableAjaxValidation' => true,
]); ?>

<?= !empty($designations) ? $form->field($designationModel, 'position_id')->widget(Select2::classname(), [
        'data' => $designations,
        'options' => ['multiple' => false, 'placeholder' => 'Select designations', 'class'=>'designation-select-view', 'id'=>'designation-select-view'],
        'pluginOptions' => [
            'allowClear' =>  false,
        ],
        'pluginEvents' => [
            'change' => 'function() { 
                viewSelectedDesignation("'.$model->emp_id.'", this.value); 
            }',
        ],
    ])->label('Choose designation to view competencies') : ''
?>

<?php ActiveForm::end(); ?>

<?php
    Modal::begin([
        'id' => 'select-designation-modal',
        'size' => "modal-md",
        'header' => '<div id="select-designation-modal-header"><h4>Select Designation</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="select-designation-modal-content"></div>';
    Modal::end();
?>

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

        $(document).ready(function(){
            $('#select-designation-button').click(function(){
                $('#select-designation-modal').modal('show').find('#select-designation-modal-content').load($(this).attr('value'));
            });

            
        });  
    ";

    $this->registerJs($script, View::POS_END);
?>

<?php
    $script = !empty($designations) ? "
        $(document).ready(function(){
            viewSelectedDesignation('".$model->emp_id."', '".$currentDesignation->position_id."'); 
            viewSelectedDesignationCompetency('".$model->emp_id."', '".$currentDesignation->position_id."'); 
        });  
    " : "";

    $this->registerJs($script, View::POS_END);
?>



