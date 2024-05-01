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
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'lsp_name') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'address') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'contact_no') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <?= $form->field($model, 'specialization') ?>
                </div>
                <div class="col-md-2 col-xs-12">
                    <label for="" style="margin-bottom: 20px;"></label>
                    <br>
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    &nbsp;&nbsp;
                    <?= Html::a('Reset', ['/npis/lsp'],['class' => 'btn btn-default']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
