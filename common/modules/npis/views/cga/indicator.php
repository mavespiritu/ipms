<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\web\View;
?>

<div class="competency-information">
    <h4>Competency Indicator Information</h4>
    <small>
        Competency: <b><?= $indicator->competency->competency ?></b><br>
        Proficiency Level: <b><?= $indicator->proficiency ?></b><br>
        Indicator: <b><?= $indicator->indicator ?></b>
    </small>
    <br>
    <br>
    <div class="nav-tabs-custom">
    <?= Tabs::widget([
        'id' => 'competency-tabs',
        'class' => 'nav-tabs-custom',
        'items' => [
            [
                'label' => 'Evidences',
                'content' => '<div id="evidences"></div>',
                'headerOptions' => ['onclick' => 'viewEvidences("'.$indicator->id.'")'],
            ],
            [
                'label' => 'Proposed Trainings',
                'content' => '<div id="trainings"></div>',
                'headerOptions' => ['onclick' => 'viewTrainings("'.$indicator->id.'")'],
            ],
        ],
    ]); ?>
    </div>
</div>
<?php
    $script = '
        $(document).ready(function(){
            viewEvidences('.$indicator->id.');
        });

        function viewEvidences(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-evidences']).'?id=" + id,
                beforeSend: function(){
                    $("#evidences").html("<div class=\"text-center\" style=\"height: calc(100vh - 445px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#evidences").empty();
                    $("#evidences").hide();
                    $("#evidences").fadeIn("slow");
                    $("#evidences").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewTrainings(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-trainings']).'?id=" + id,
                beforeSend: function(){
                    $("#trainings").html("<div class=\"text-center\" style=\"height: calc(100vh - 445px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#trainings").empty();
                    $("#trainings").hide();
                    $("#trainings").fadeIn("slow");
                    $("#trainings").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>