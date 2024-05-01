<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-search"></i> Search Records</h3></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'service_provider_id')->widget(Select2::classname(), [
                        'data' => $lsps,
                        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'lsp-select'],
                        'pluginOptions' => [
                            'allowClear' =>  true,
                        ],
                    ])->label('Name of LSP')
                    ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'training_title') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'no_of_hours') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'cost')->widget(MaskedInput::classname(), [
                        'options' => [
                            'autocomplete' => 'off',
                        ],
                        'clientOptions' => [
                            'alias' =>  'decimal',
                            'removeMaskOnSubmit' => true,
                            'groupSeparator' => ',',
                            'autoGroup' => true,
                        ],
                    ])->label('Cost') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'modality')->widget(Select2::classname(), [
                        'data' => ['Hybrid' => 'Hybrid', 'F2F' => 'F2F', 'Virtual' => 'Virtual'],
                        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'modality-select'],
                        'pluginOptions' => [
                            'allowClear' =>  true,
                        ],
                        ])
                    ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label for="" style="margin-bottom: 20px;"></label>
                    <br>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    &nbsp;&nbsp;
                    <?= Html::a('Reset', ['/npis/training'],['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
