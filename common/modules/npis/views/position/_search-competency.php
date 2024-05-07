<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="Competency-search">

    <?= Select2::widget([
        'name' => 'comp_id',
        'data' => $competencies,
        'options' => [
            'placeholder' => 'Select competency',
            'multiple' => false,
            'onchange' => 'selectCompetency(this.value, "'.$model->item_no.'")',
        ],
        'pluginOptions' => [
            'allowClear' =>  true,
        ],
    ])
    ?>

</div>
