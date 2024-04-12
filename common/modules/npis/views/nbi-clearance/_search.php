<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\EmployeeMedicalCertificateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nbi-clearance-search">

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
                    <?= $form->field($model, 'from_date')->widget(DatePicker::className(), [
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'options' => ['placeholder' => 'Enter date', 'autocomplete' => 'off'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ],
                    ]); ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label for="" style="margin-bottom: 20px;"></label>
                    <br>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    &nbsp;&nbsp;
                    <?= Html::a('Reset', ['/npis/nbi-clearance'],['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
