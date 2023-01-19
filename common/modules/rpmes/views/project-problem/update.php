<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\rpmes\models\ProjectProblem */

$this->title = 'Update Record';
$this->params['breadcrumbs'][] = ['label' => 'Form 11: Key Lessons Learned from Issues Resolved at the RPMC Level', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="project-problem-update">
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <?= $this->render('_form', [
                'model' => $model,
                'projects' => $projects,
            ]) ?>
        </div>
    </div>
</div>
