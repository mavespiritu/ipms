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

<?= Html::button('Add position on my career path', ['value' => Url::to(['/npis/cga/select-position', 'emp_id' => $model->emp_id]), 'class' => 'btn btn-success btn-block', 'id' => 'select-position-button']) ?>

<br>

<div class="position-select-form">

    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'position-view-form', 'enctype' => 'multipart/form-data', 'method' => 'post'],
        //'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($careerModel, 'position_id')->widget(Select2::classname(), [
            'data' => $positions,
            'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'position-select-view', 'id'=>'position-select-view'],
            'pluginOptions' => [
                'allowClear' =>  true,
            ],
            'pluginEvents' => [
                'change' => 'function() { 
                    viewPositionCompetencies("'.$model->emp_id.'", this.value); 
                    viewSelectedCareer("'.$model->emp_id.'", this.value); 
                }',
            ],
        ])->label('Choose position to view required competencies')
    ?>

    <?php ActiveForm::end(); ?>

</div>

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
    Modal::begin([
        'id' => 'select-position-modal',
        'size' => "modal-md",
        'header' => '<div id="select-position-modal-header"><h4>Select Position</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="select-position-modal-content"></div>';
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
            $('#select-position-button').click(function(){
                $('#select-position-modal').modal('show').find('#select-position-modal-content').load($(this).attr('value'));
            });
        });  
    ";

    $this->registerJs($script, View::POS_END);
?>
