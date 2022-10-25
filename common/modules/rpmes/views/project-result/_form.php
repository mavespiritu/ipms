<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\modules\rpmes\models\ProjectResult */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-result-form">

    <?php $form = ActiveForm::begin([
    	'options' => ['class' => 'disable-submit-buttons'],
    ]); ?>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?= $form->field($model, 'year')->widget(Select2::classname(), [
                'data' => $years,
                'options' => ['multiple' => false, 'placeholder' => 'Select One', 'class'=>'quarter-select'],
                'pluginOptions' => [
                    'allowClear' =>  true,
                ],
                ])->label('Year *');
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?= $form->field($model, 'project_id')->widget(Select2::classname(), [
                'data' => $projects,
                'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'project-select'],
                'pluginOptions' => [
                    'allowClear' =>  true,
                ],
            ])->label('Project *:');
            ?>
        </div>
    </div>

    <?= $form->field($model, 'objective')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'results_indicator')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'observed_results')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'deadline')->widget(DatePicker::className(), [
                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'startDate' => '0d'
                ],
    ])->label('Deadline *'); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'data' => ['disabled-text' => 'Please Wait']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
