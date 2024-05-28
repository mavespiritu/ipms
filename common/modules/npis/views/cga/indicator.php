<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Tabs;
use yii\web\View;
?>

<div class="indicator-information">
    <h4>Competency Indicator Information</h4>
    <small>
        <div class="table-responsive">
            <table class="table table-condensed table-responsive">
                <tbody>
                    <tr>
                        <td>Competency:</td>
                        <td><b><?= $indicator->competency->competency ?></b></td>
                        <td>Proficiency Level:</td>
                        <td><b><?= $indicator->proficiency ?></b></td>
                        <td>Indicator:</td>
                        <td><b><?= $indicator->indicator ?></b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </small>

    <div class="nav-tabs-custom">
    <?= Tabs::widget([
        'id' => 'indicator-tabs',
        'class' => 'nav-tabs-custom',
        'encodeLabels' => false,
        'items' => [
            [
                'label' => 'Evidences <span class="badge bg-green" id="evidence-badge-'.$indicator->id.'">'.$staffAllIndicatorModel->getStaffCompetencyIndicatorEvidences()->count().'</span>',
                'content' => '<div id="evidences-'.$tab.'"></div>',
                'headerOptions' => ['onClick' => 'viewEvidences("'.$indicator->id.'", "'.$model->emp_id.'", "'.$tab.'")'],
            ],
            [
                'label' => 'Proposed Trainings <span class="badge bg-green" id="training-badge-'.$indicator->id.'">0</span>',
                'content' => '<div id="trainings-'.$tab.'"></div>',
                'headerOptions' => ['onClick' => 'viewTrainings("'.$indicator->id.'", "'.$tab.'")'],
            ],
        ],
    ]); ?>
    </div>
</div>
<?php
    $script = '
        $(document).ready(function(){
            viewEvidences('.$indicator->id.', "'.$model->emp_id.'", "'.$tab.'");
        });

        function viewEvidences(id, emp_id, tab)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-evidences']).'?id=" + id + "&emp_id=" + emp_id + "&tab=" + tab,
                beforeSend: function(){
                    $("#evidences").html("<div class=\"text-center\" style=\"height: calc(100vh - 445px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#evidences-'.$tab.'").empty();
                    $("#evidences-'.$tab.'").hide();
                    $("#evidences-'.$tab.'").fadeIn("slow");
                    $("#evidences-'.$tab.'").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewTrainings(id, tab)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-trainings']).'?id=" + id + "&tab=" + tab,
                beforeSend: function(){
                    $("#trainings-'.$tab.'").html("<div class=\"text-center\" style=\"height: calc(100vh - 445px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#trainings-'.$tab.'").empty();
                    $("#trainings-'.$tab.'").hide();
                    $("#trainings-'.$tab.'").fadeIn("slow");
                    $("#trainings-'.$tab.'").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>