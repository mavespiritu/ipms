<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'My CGA';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<?php
    if(Yii::$app->session->hasFlash('success')):?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif;
    if(Yii::$app->session->hasFlash('error')):?>
        <div class="alert alert-danger" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif;
?>
<div id="alert" class="alert" role="alert" style="display: none;"></div>
<div class="row">
    <div class="col-md-12  col-md-4 col-lg-4 col-xs-12">
        <h4>Designations</h4>
        <div id="current-designation"></div>
        <div id="selected-designation"></div>
        <h4>Required Competencies</h4>
        <div id="designation-competencies">
            <div class="flex-center" style="height: calc(100vh - 315px);">
                <h4 style="color: gray;">Select designation above to view required competencies.</h4>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8 col-lg-8 col-xs-12">
        <div id="indicator-information-designation"></div>
    </div>
</div>

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
        function viewCurrentDesignation(emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-current-designation']).'?emp_id=" + emp_id,
                beforeSend: function(){
                    $("#current-designation").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#current-designation").empty();
                    $("#current-designation").hide();
                    $("#current-designation").fadeIn("slow");
                    $("#current-designation").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewDesignationCompetencies(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-designation-competencies']).'?id=" + id,
                beforeSend: function(){
                    $("#designation-competencies").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#designation-competencies").empty();
                    $("#designation-competencies").hide();
                    $("#designation-competencies").fadeIn("slow");
                    $("#designation-competencies").html(data);
                    $("#indicator-information-designation").empty();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewSelectedDesignation(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-selected-designation']).'?id=" + id,
                beforeSend: function(){
                    $("#selected-designation").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#selected-designation").empty();
                    $("#selected-designation").hide();
                    $("#selected-designation").fadeIn("slow");
                    $("#selected-designation").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewSelectedDesignationCompetency(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-selected-designation-competency']).'?id=" + id,
                beforeSend: function(){
                    $("#designation-competency-indicator-information").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#designation-competency-indicator-information").empty();
                    $("#designation-competency-indicator-information").hide();
                    $("#designation-competency-indicator-information").fadeIn("slow");
                    $("#designation-competency-indicator-information").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            viewCurrentDesignation("'.$model->emp_id.'");
        });
        
    ';

    $this->registerJs($script, View::POS_END);
?>

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