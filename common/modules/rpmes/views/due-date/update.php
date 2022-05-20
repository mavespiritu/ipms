<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\rpmes\models\DueDate */

$this->title = 'Update Record';
$this->params['breadcrumbs'][] = ['label' => 'Due Dates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="due-date-update">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
