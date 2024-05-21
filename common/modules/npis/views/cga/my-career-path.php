<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\web\View;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
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
    <div class="col-sm-12 col-md-3 col-lg-3 col-xs-12">
        <h4>My Career Path</h4>
        <?= Html::button('Select Position', ['value' => Url::to(['/npis/cga/select-position', 'emp_id' => $model->emp_id]), 'class' => 'btn btn-success btn-block', 'id' => 'select-position-button']) ?>
        <br>
        <div id="career-path"></div>
    </div>
    <div class="col-sm-12 col-md-9 col-lg-9 col-xs-12">
        <div class="row">
            <div class="col-md-5 col-xs-12 col-sm-12 col-lg-5">
                <h4>Required Competencies</h4>
                <div id="career-path-competencies">
                    <div class="flex-center" style="height: calc(100vh - 315px);">
                        <h4 style="color: gray;">Select position at the left to view required competencies.</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-xs-12 col-sm-12 col-lg-7">
                <div id="indicator-information-career-path">
                    
                </div>
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
    Modal::begin([
        'id' => 'select-position-modal',
        'size' => "modal-md",
        'header' => '<div id="select-position-modal-header"><h4>Select Position</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="select-position-modal-content"></div>';
    Modal::end();
?>

<?php
    $script = '
        function viewCareerPath(emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-career-path']).'?emp_id=" + emp_id,
                beforeSend: function(){
                    $("#career-path").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#career-path").empty();
                    $("#career-path").hide();
                    $("#career-path").fadeIn("slow");
                    $("#career-path").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewPositionCompetencies(emp_id, position_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/view-position-competencies']).'?emp_id=" + emp_id + "&position_id=" + position_id,
                beforeSend: function(){
                    $("#career-path-competencies").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#career-path-competencies").empty();
                    $("#career-path-competencies").hide();
                    $("#career-path-competencies").fadeIn("slow");
                    $("#career-path-competencies").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            $("#select-position-button").click(function(){
                $("#select-position-modal").modal("show").find("#select-position-modal-content").load($(this).attr("value"));
            });

            viewCareerPath("'.$model->emp_id.'");
        });     
    ';

    $this->registerJs($script, View::POS_END);
?>