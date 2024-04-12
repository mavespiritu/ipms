<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\View;
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */

$selectedStaffProfile = '<script>localStorage.getItem("selectedStaffProfile")</script>';

?>

<div class="ipcr-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <div class="row">
        <div class="col-md-4 col-xs-12">
            <?= $form->field($model, 'emp_id')->widget(Select2::classname(), [
                'data' => \common\models\Employee::getAllList(),
                'options' => [
                    'multiple' => false, 
                    'placeholder' => 'Select one', 
                    'class' => 'employee-select',
                    'value' => $selectedStaffProfile
                ],
                'pluginOptions' => [
                    'allowClear' =>  false,
                ],
                'pluginEvents' => [
                    'change' => 'function() { 
                        viewStaffProfile(this.value); 
                        localStorage.setItem("selectedStaffProfile", this.value);
                    }',
                ],
            ])->label('Search staff')
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $selectedStaffProfile = Yii::$app->session->get('selectedStaffProfile');
    $staffPdsLastTab = Yii::$app->session->get('staffPdsLastTab');
    $script = '
        function viewStaffProfile(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-staff-profile']).'?id=" + id,
                beforeSend: function(){
                    $("#staff-profile").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#staff-profile").empty();
                    $("#staff-profile").hide();
                    $("#staff-profile").fadeIn("slow");
                    $("#staff-profile").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            var selectedStaffProfile = "'.$selectedStaffProfile.'" != "" ? "'.$selectedStaffProfile.'" : localStorage.getItem("selectedStaffProfile");
            localStorage.setItem("selectedStaffProfile", selectedStaffProfile);

            if(selectedStaffProfile){
                var staffPdsLastTab = "'.$staffPdsLastTab.'" != "" ? "'.$staffPdsLastTab.'" : localStorage.getItem("staffPdsLastTab");
                localStorage.setItem("staffPdsLastTab", staffPdsLastTab);

                $(".employee-select").val(selectedStaffProfile);
                viewStaffProfile(selectedStaffProfile);
            }
        });
    ';

    $this->registerJs($script, View::POS_END);
?>