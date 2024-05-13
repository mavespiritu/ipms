<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\web\View;
use yii\bootstrap\Tabs;

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
    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12">
        <h4>My Current Position</h4>
        <small>
            Item No.: <b><?= $model->item_no ?></b><br>
            Position: <b><?= $model->positionItem->position_id ?></b><br>
            Division: <b><?= $model->positionItem->division_id ?></b><br>
            SG and Step: <b><?= $model->positionItem->grade.'-'.$model->positionItem->step ?></b>
        </small>
        <br>
        <h4>Required Competencies</h4>
        <div id="competencies"></div>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12">
        <div id="indicator-information" style="height: calc(100vh - 315px);">
            <div class="flex-center" style="height: 100%;">
                <h4 style="color: gray;">Select indicator at the left to add or view evidences.</h4>
            </div>
        </div>
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
        function viewCompetencies()
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-competencies']).'",
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

        function viewIndicator(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-indicator']).'?id=" + id,
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
            viewCompetencies("'.$model->item_no.'");
        });
    ';

    $this->registerJs($script, View::POS_END);
?>