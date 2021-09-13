<?php
use yii\helpers\Html;
?>

<div>
    <div class="pull-left">
        <?= Html::a('<i class="fa fa-table"></i> Programs and Objects', ['view', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
        <?= Html::a('<i class="fa fa-table"></i> Input Amounts', ['form', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
        <?php // Html::a('<i class="fa fa-table"></i> Allocate Amounts', ['allocate', 'id' => $model->id], ['class' => 'btn btn-app']) ?>
    </div>
    <div class="pull-right">
        <?= Html::a('<i class="fa fa-trash"></i> Delete GAA', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-app',
            'data' => [
                'confirm' => 'Deleting this item will also delete the amounts in the GAA form. Would you like to proceed?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    <div class="clearfix"></div>
</div>