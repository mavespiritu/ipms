<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveField;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use yii\web\View;
use yii\widgets\MaskedInput;
use kartik\daterange\DateRangePicker;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use \file\components\AttachmentsInput;
use yii\web\JsExpression;
use buttflatteryormwizard\FormWizard;
use dosamigos\switchery\Switchery;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\training */
/* @var $form yii\widgets\ActiveForm */

function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    
    return $randomString;
}

// Example usage:
$tab = generateRandomString(20);

?>
<p><?= $competency->description ?></p>
<?php if(!empty($availableDescriptors)){ ?>
    <?php foreach($availableDescriptors as $proficiency => $competencies){ ?>
        <div class="table-responsive">
            <table class="table table-responsive table-condensed table-bordered table-hover">
                <thead>
                    <tr>
                        <th colspan=2>LEVEL <?= $proficiency ?></th>
                        <th><?= Switchery::widget([
                            'name' => 'is_required', 
                            'options' => [
                                'label' => false,
                                'title' => '',
                                'id' => $tab.'-competency-'.$competency->comp_id.'-'.$proficiency.'-compliance',
                                'checked' => ($checkCompetencyProficiencies[$proficiency] > 0 && $checkCompetencyProficiencies[$proficiency] == count($availableDescriptors[$proficiency])) ? true : false
                            ],
                            'clientOptions' => [
                                'color' => '#5fbeaa',
                                'size' => 'small'
                            ],
                            'clientEvents' => [
                                'change' => new JsExpression('function() {
                                    var isChecked = this.checked;
                                    $(".'.$tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-compliance").each(function() {
                                        $(this).prop("checked", !isChecked);
                                        $(this).val(isChecked ? 1 : 0);
                                        $(this).trigger("click");
                                    });
                                }')
                            ]
                        ]) ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($competencies)){ ?>
                    <?php foreach($competencies as $idx => $descriptor){ ?>
                        <?php $id = $descriptor['id']; ?>
                        <?php $form = ActiveForm::begin([
                            'id' => $tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-'.$descriptor['id'].'-form',
                            'method' => 'POST',
                            'class' => 'indicator-form',
                        ]); ?>
                        <tr>
                            <td><?= $idx + 1 ?></td>
                            <td><?= Html::a($descriptor['indicator'], 'javascript:void(0);', ['onclick' => 'viewIndicator('.$descriptor['id'].',"'.$model->emp_id.'", "'.$tab.'")']) ?></td>
                            <td><?= $form->field($descriptorModels[$proficiency][$id], '[$proficiency][$id]compliance')->widget(Switchery::className(), [
                                        'options' => [
                                            'label' => '',
                                            'id' => $tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-'.$descriptor['id'].'-compliance',
                                            'class' => $tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-compliance',
                                            'value' => $descriptorModels[$proficiency][$id]['compliance'],
                                            'checked' => $descriptorModels[$proficiency][$id]['compliance'] == 1 ? true : false
                                        ],
                                        'clientOptions' => [
                                            'color' => '#5fbeaa',
                                            'size' => 'small',
                                        ],
                                        'clientEvents' => [
                                            'change' => new JsExpression('debounce(function() {
                                                this.value = this.checked ? 1 : 0;
                                                submitForm($("#'.$tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-'.$descriptor['id'].'-form"), '.$proficiency.', '.$descriptor['id'].', this.value);
                                            }, 250)'),
                                        ]
                                    ])->label(false) ?>
                                    <span id=<?= $tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-'.$descriptor['id'].'-compliance-loader' ?>></span>
                            </td>
                        </tr>
                        <?php
                        $this->registerJs('
                            $(document).ready(function(){
                                var input = $("#'.$tab.'-competency-indicator-'.$competency->comp_id.'-'.$proficiency.'-'.$descriptor['id'].'-compliance");
                                var label = input.closest("label");
                                var smallElem =  label.find("span.switchery > small");
                                $(smallElem).css("left", input.val() == 1 ? "13px" : "0px");
                            });
                            ');
                        ?>
                        <?php ActiveForm::end(); ?>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
<?php } ?>
<?php
  $script = '
    function submitForm(form, proficiency, id, value)
    {   
        var formData = form.serialize();

        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: {
                id: id,
                value: value
            },
            beforeSend: function(){
                $("#'.$tab.'-competency-indicator-'.$competency->comp_id.'-" + proficiency + "-" + id + "-compliance-loader").html("<div class=\"text-center\" style=\"display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"10px\" height=\"10px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
            },
            success: function (data) {
                $("#'.$tab.'-competency-indicator-'.$competency->comp_id.'-" + proficiency + "-" + id + "-compliance-loader").empty();
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    // Debounce function to prevent multiple rapid calls
    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                timeout = null;
                func.apply(context, args);
            }, wait);
        };
    }

  ';
  $this->registerJs($script, View::POS_END);
?>