<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\View;
/* @var $model common\modules\npis\models\IpcrSearch */
/* @var $form yii\widgets\ActiveForm */

$selectedStaffCgaProfile = '<script>localStorage.getItem("selectedStaffCgaProfile")</script>';

?>

<div class="ipcr-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'post',
    ]); ?>

    <?= $form->field($model, 'emp_id')->widget(Select2::classname(), [
        'data' => \common\models\Employee::getList(),
        'options' => [
            'multiple' => false, 
            'placeholder' => 'Select one', 
            'class' => 'employee-select',
            'value' => $selectedStaffCgaProfile
        ],
        'pluginOptions' => [
            'allowClear' =>  false,
        ],
        'pluginEvents' => [
            'change' => 'function() { 
                viewStaffCgaProfile(this.value); 
                localStorage.setItem("selectedStaffCgaProfile", this.value);
            }',
        ],
    ])->label('Search staff')
    ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
    $selectedCgaStaffProfile = Yii::$app->session->get('selectedCgaStaffProfile');
    $script = '
        function viewStaffCgaProfile(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-staff-cga-profile']).'?id=" + id,
                beforeSend: function(){
                    $("#staff-cga-profile").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#indicator-information").empty();
                    $("#staff-cga-profile").empty();
                    $("#staff-cga-profile").hide();
                    $("#staff-cga-profile").fadeIn("slow");
                    $("#staff-cga-profile").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            var selectedCgaStaffProfile = "'.$selectedCgaStaffProfile.'" != "" ? "'.$selectedCgaStaffProfile.'" : localStorage.getItem("selectedCgaStaffProfile");
            localStorage.setItem("selectedCgaStaffProfile", selectedCgaStaffProfile);

            if(selectedStaffProfile){
                $(".employee-select").val(selectedCgaStaffProfile);
                selectedCgaStaffProfile(selectedCgaStaffProfile);
            }
        });
    ';

    $this->registerJs($script, View::POS_END);
?>