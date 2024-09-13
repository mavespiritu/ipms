<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\DesignationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="career-path-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'emp_id') ?>

    <?= $form->field($model, 'position_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'end_date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
