<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = $model->item_no;
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = ['label' => 'Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Set Job Description'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-job-description">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Job Description Setup Form</h3></div>
        <div class="box-body">
            <?= $this->render('_job-description-form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
