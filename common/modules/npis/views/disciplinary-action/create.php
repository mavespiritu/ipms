<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'Add New Record';
$this->params['breadcrumbs'][] = 'NPIS';
$this->params['breadcrumbs'][] = 'Staff 201';
$this->params['breadcrumbs'][] = ['label' => 'Disciplinary Action', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="special-order-create">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Record Entry Form</h3></div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
                'idModel' => $idModel,
                'deviances' => $deviances,
            ]) ?>
        </div>
    </div>
</div>
