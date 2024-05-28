<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\web\View;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Cga */

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
        <h4>All Competencies</h4>
        <div id="all-competencies"></div>
    </div>
    <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12">
        <div id="indicator-information-all-competencies">
            <div class="flex-center" style="height: calc(100vh - 315px);">
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
        function viewAllCompetencies(emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-all-competencies']).'?emp_id=" + emp_id,
                beforeSend: function(){
                    $("#all-competencies").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#all-competencies").empty();
                    $("#all-competencies").hide();
                    $("#all-competencies").fadeIn("slow");
                    $("#all-competencies").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            viewAllCompetencies("'.$model->emp_id.'");
        });
    ';

    $this->registerJs($script, View::POS_END);
?>