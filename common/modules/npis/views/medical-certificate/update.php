<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'Update Record';
$this->params['breadcrumbs'][] = 'NPIS';
$this->params['breadcrumbs'][] = 'Staff 201';
$this->params['breadcrumbs'][] = ['label' => 'Medical Certificate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medical-certificate-update">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Record Entry Form</h3></div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
                'idModel' => $idModel,
            ]) ?>
        </div>
    </div>
</div>
