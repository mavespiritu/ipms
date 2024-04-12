<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ipcr-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-search"></i> Search Records</h3></div>
        <div class="panel-body">
            <div class="row">
                <?php if(Yii::$app->user->can('HR')){ ?>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'emp_id')->widget(Select2::classname(), [
                        'data' => \common\models\Employee::getAllList(),
                        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'employee-select'],
                        'pluginOptions' => [
                            'allowClear' =>  true,
                        ],
                    ])->label('Name of Staff')
                    ?>
                </div>
                <?php } ?>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'year') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'semester')->widget(Select2::classname(), [
                        'data' => [ 1 => '1st Semester', 2 => '2nd Semester'],
                        'options' => ['multiple' => false, 'placeholder' => 'Select one', 'class'=>'semester-select'],
                        'pluginOptions' => [
                            'allowClear' =>  true,
                        ],
                        ])
                    ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'rating') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label for="" style="margin-bottom: 20px;"></label>
                    <br>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    &nbsp;&nbsp;
                    <?= Html::a('Reset', ['/npis/ipcr'],['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
