<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-search"></i> Search Records</h3></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'item_no') ?>
                </div>

                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'position_id') ?>
                </div>

                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'division_id')->widget(Select2::classname(), [
                        'data' => $divisions,
                        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'division-select'],
                        'pluginOptions' => [
                            'allowClear' =>  true,
                        ],
                    ])->label('Division')
                    ?>
                </div>
                
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'grade') ?>
                </div>

                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'step') ?>
                </div>

                <div class="col-md-2 col-xs-12">
                    <label for="" style="margin-bottom: 20px;"></label>
                    <br>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    &nbsp;&nbsp;
                    <?= Html::a('Reset', ['/npis/position'],['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
