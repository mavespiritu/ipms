<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\modules\dtr\models\Dtr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dtr-form">

    <?php $form = ActiveForm::begin([
        'id' => 'fwa-form',
        'options' => ['class' => 'disable-submit-buttons'],
    ]); ?>

    <div class="form-group pull-right">
        <?= Html::submitButton('Record Time Entry', ['class' => 'btn btn-success', 'data' => ['disabled-text' => 'Please Wait']]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $script = '
    $("#fwa-form").on("beforeSubmit", function (e) {
        e.preventDefault();

        var form = $(this);
        var formData = form.serialize();

        var con = confirm("Are you sure you want to record your time entry?");
        if(con == true)
        {
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                beforeSend: function(){
                    
                },
                success: function (data) {
                    form.enableSubmitButtons();
                    updateAmIn();
                    updateAmOut();
                    updatePmIn();
                    updatePmOut();
                },
                error: function (err) {
                    console.log(err);
                }
            });      
        }else{
            form.enableSubmitButtons();
        }

        return false;
    });
    ';

    $this->registerJs($script, View::POS_END);
?>