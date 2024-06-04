<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competency-search">

    <?= Select2::widget([
        'name' => 'comp_id',
        'data' => $competencies,
        'options' => [
            'id' => 'designation-compentencies-list',
            'placeholder' => 'Select competency',
            'multiple' => false,
            'onchange' => 'selectDesignationCompetency(this.value, "'.$model->position_id.'", "'.$model->emp_id.'")',
        ],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
    ])
    ?>

</div>
