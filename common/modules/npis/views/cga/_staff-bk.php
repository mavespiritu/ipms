<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'Staff CGA';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="cga-staff-profile-view">
    <?php if($model){ ?>
        <small>
            <table class="table table-condensed table-responsive">
                <tr>
                    <td>Item No.:</td>
                    <td><b><?= $model->item_no ?></b></td>
                    <td>Position:</td>
                    <td><b><?= $model->positionItem->position_id ?></b></td>
                </tr>
                <tr>
                    <td>Division:</td>
                    <td><b><?= $model->positionItem->division_id ?></b></td>
                    <td>SG and Step:</td>
                    <td><b><?= $model->positionItem->grade.'-'.$model->positionItem->step ?></b></td>
                </tr>
            </table>
        </small>
        <h4>Required Competencies</h4>
        <div id="competencies"></div>
    <?php
        if ($successMessage) {
            $this->registerJs("
                $(document).ready(function() {
                    // Display the flash message
                    $('.alert-success').fadeIn();

                    // Hide the flash message after 5 seconds
                    setTimeout(function() {
                        $('.alert-success').fadeOut();
                    }, 3000);
                });
            ");
        }
        ?>

        <?php
            $script = '
                function viewCompetencies(emp_id)
                {
                    $.ajax({
                        url: "'.Url::to(['/npis/cga/view-competencies']).'?emp_id=" + emp_id,
                        beforeSend: function(){
                            $("#competencies").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                        },
                        success: function (data) {
                            console.log(this.data);
                            $("#competencies").empty();
                            $("#competencies").hide();
                            $("#competencies").fadeIn("slow");
                            $("#competencies").html(data);
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }

                function viewIndicator(id, emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-indicator']).'?id=" + id + "&emp_id=" + emp_id,
                beforeSend: function(){
                    $("#indicator-information").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#indicator-information").empty();
                    $("#indicator-information").hide();
                    $("#indicator-information").fadeIn("slow");
                    $("#indicator-information").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

                $(document).ready(function(){
                    viewCompetencies("'.$model->emp_id.'");
                });
            ';

            $this->registerJs($script, View::POS_END);
        ?>
    <?php }else{ ?>
        <p>No attached plantilla position to the staff.</p>
    <?php } ?>
</div>
