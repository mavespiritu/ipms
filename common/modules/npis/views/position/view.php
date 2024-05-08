<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = $model->item_no;
$this->params['breadcrumbs'][] = ['label' => 'CGA'];
$this->params['breadcrumbs'][] = ['label' => 'Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Set Competency'];
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
<div class="ipcr-view">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Competency Setup Form</h3></div>
        <div class="box-body">
            <div class="user-block">
                <span class="description" style="margin-left: 0 !important;">
                    Item No.: <?= $model->item_no ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Position: <?= $model->position_id ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Division: <?= $model->division_id ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    SG and Step: <?= $model->grade.'-'.$model->step ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-12">
                    <h5>Available Competencies</h5>
                    <?= $this->render('_search-competency', [
                        'model' => $model,
                        'competencies' => $competencies
                    ]) ?>
                    <br>
                    <div id="competency-list" style="height: calc(100vh - 290px); overflow-y: auto; padding: 10px;">
                        <div class="flex-center" style="height: 100%;">
                            <h4 style="color: gray;">Select competency above to view indicators</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xs-12">
                    <h5>Included Competencies for Item No. <?= $model->item_no ?></h5>
                    <div id="competency-information">
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $script = '
        function selectCompetency(id, position_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/position/select-competency']).'?id=" + id + "&position_id=" + position_id,
                beforeSend: function(){
                    $("#competency-list").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#competency-list").empty();
                    $("#competency-list").hide();
                    $("#competency-list").fadeIn("slow");
                    $("#competency-list").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewCompetency(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/position/view-competency']).'?id=" + id,
                beforeSend: function(){
                    $("#competency-information").html("<div class=\"text-center\"><svg class=\"spinner\" width=\"20px\" height=\"20px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#competency-information").empty();
                    $("#competency-information").hide();
                    $("#competency-information").fadeIn("slow");
                    $("#competency-information").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            viewCompetency("'.$model->item_no.'");
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