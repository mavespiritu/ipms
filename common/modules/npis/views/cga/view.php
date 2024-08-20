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
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div id="alert" class="alert" role="alert" style="display: none;"></div>
<div class="ipcr-view">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Competency Gap Analysis</h3></div>
        <div class="box-body">
            <div class="nav-tabs-custom">
                <?= Tabs::widget([
                    'id' => 'my-cga-tabs',
                    'class' => 'nav-tabs-custom',
                    'items' => [
                        [
                            'label' => 'Current Position',
                            'content' => '<div id="my-current-position"></div>',
                            'headerOptions' => ['onclick' => 'viewMyCurrentPosition("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Career Path',
                            'content' => '<div id="my-career-path"></div>',
                            'headerOptions' => ['onclick' => 'viewMyCareerPath("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'All Competencies',
                            'content' => '<div id="my-competencies"></div>',
                            'headerOptions' => ['onclick' => 'viewMyCompetencies("'.$model->emp_id.'")'],
                        ],
                    ],
                ]); ?>
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
        function viewMyCurrentPosition(emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/my-current-position']).'?emp_id=" + emp_id,
                beforeSend: function(){
                    $("#my-current-position").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#my-current-position").empty();
                    $("#my-current-position").hide();
                    $("#my-current-position").fadeIn("slow");
                    $("#my-current-position").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewMyCareerPath(emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/my-career-path']).'?emp_id=" + emp_id,
                beforeSend: function(){
                    $("#my-career-path").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#my-career-path").empty();
                    $("#my-career-path").hide();
                    $("#my-career-path").fadeIn("slow");
                    $("#my-career-path").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewMyCompetencies(emp_id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/cga/my-competencies']).'?emp_id=" + emp_id,
                beforeSend: function(){
                    $("#my-current-position").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    console.log(this.data);
                    $("#my-competencies").empty();
                    $("#my-competencies").hide();
                    $("#my-competencies").fadeIn("slow");
                    $("#my-competencies").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        $(document).ready(function(){
            viewMyCurrentPosition("'.$model->emp_id.'");
        });
    ';

    $this->registerJs($script, View::POS_END);
?>