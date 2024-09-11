<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'Update Record';
$this->params['breadcrumbs'][] = 'Competencies';
$this->params['breadcrumbs'][] = ['label' => 'Trainings', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ipcr-update">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Record Entry Form</h3></div>
        <div class="box-body">
            <?= $this->render('_form', [
                'model' => $model,
                'competencyModel' => $competencyModel,
                'lsps' => $lsps,
                'competencies' => $competencies
            ]) ?>
        </div>
    </div>
</div>
