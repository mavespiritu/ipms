<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\View;
/* @var $model common\modules\v1\models\PpmpSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
        'id' => 'dashboard-form'
    ]); ?>

    <div class="row">
        
        <div class="col-md-3 col-xs-12">
        <?= $form->field($model, 'stage')->widget(Select2::classname(), [
                'data' => $stages,
                'options' => ['placeholder' => 'Select Stage', 'multiple' => false, 'class'=>'stage-select'],
                'pluginOptions' => [
                    'allowClear' =>  false,
                ],
            ]);
        ?>
        </div>

        <div class="col-md-3 col-xs-12">
        <?= $form->field($model, 'year')->widget(Select2::classname(), [
                'data' => $years,
                'options' => ['placeholder' => 'Select Year', 'multiple' => false, 'class'=>'year-select'],
                'pluginOptions' => [
                    'allowClear' =>  false,
                ],
            ]);
        ?>
        </div>

        <div class="col-md-2 col-xs-12">
            <div class="form-group flex-center" style="margin-top: 25px;">
                <label>&nbsp;</label>
                <?= Html::submitButton('Preview', ['class' => 'btn btn-primary btn-block']) ?>&nbsp;&nbsp;
                <?= Html::resetButton('Clear', ['class' => 'btn btn-outline-secondary', 'onClick' => 'redirectPage()']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = '
    function redirectPage()
    {
        window.location.href = "'.Url::to(['/v1/dashboard/']).'";
    }

    function appropriation(data)
    {
        $.ajax({
            url: "'.Url::to(['/v1/dashboard/appropriation']).'?params=" + data,
            beforeSend: function(){
                $("#appropriation").html("<div class=\"text-center\" style=\"margin-top: 50px;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
            },
            success: function (data) { 
                $("#appropriation").empty();
                $("#appropriation").hide();
                $("#appropriation").fadeIn();
                $("#appropriation").html(data);
            }
        });
    }

    function prexcSummary(data)
    {
        $.ajax({
            url: "'.Url::to(['/v1/dashboard/prexc-summary']).'?params=" + data,
            beforeSend: function(){
                $("#prexc-summary").html("<div class=\"text-center\" style=\"margin-top: 50px;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
            },
            success: function (data) { 
                $("#prexc-summary").empty();
                $("#prexc-summary").hide();
                $("#prexc-summary").fadeIn();
                $("#prexc-summary").html(data);
            }
        });
    }

    $("#dashboard-form").on("beforeSubmit", function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = JSON.stringify(form.serializeArray());

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: formData,
            success: function (data) {
                appropriation(formData);
                prexcSummary(formData);
            },
            error: function (err) {
                console.log(err);
            }
        });

        return false;
    });
';
$this->registerJs($script, View::POS_END);
?>