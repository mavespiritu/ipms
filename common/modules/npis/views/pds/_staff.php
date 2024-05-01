<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'My PDS';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pds-view">
    <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title"><?= $model->fname.' '.$model->mname.' '.$model->lname ?></h3></div>
            <div class="box-body">
                <div class="user-block">
                    <?php $base64Image = base64_encode($model->picture); ?>
                    <?= '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Image" style="height: auto; width: 130px; border: 1px solid #6699CC; border-radius: 10px; margin-right: 10px;">' ?>
                    <span class="username">
                        <span class="pull-right" style="vertical-align: top;">
                            <?= Html::a('Generate PDS', ['/npis/pds/excel', 'id' => $model->emp_id],['class' => 'btn btn-info']) ?>
                        </span>
                        <h3><?= $model->fname.' '.$model->mname.' '.$model->lname ?></h3>
                    </span>
                    <span class="description">
                        IPMS/Employee ID No.: <?= $model->emp_id ?> <br>
                        Division: <?= $model->division->division_name ?> <br>
                        Position: <?= $model->position->post_description ?> <br>
                        Date Hired: <?= date("F j, Y", strtotime($model->hire_date)) ?>
                    </span>
                </div>
                <br>
                <br>
                <?= Tabs::widget([
                    'id' => 'staff-pds-tabs',
                    'items' => [
                        [
                            'label' => 'Personal Information',
                            'content' => '<div id="personal-information"></div>',
                            'headerOptions' => ['onclick' => 'viewPersonalInformation("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Family Background',
                            'content' => '<div id="family-background"></div>',
                            'headerOptions' => ['onclick' => 'viewFamilyBackground("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Educational Background',
                            'content' => '<div id="educational-background"></div>',
                            'headerOptions' => ['onclick' => 'viewEducationalBackground("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Civil Service Eligibility',
                            'content' => '<div id="eligibility"></div>',
                            'headerOptions' => ['onclick' => 'viewEligibility("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Work Experience',
                            'content' => '<div id="work-experience"></div>',
                            'headerOptions' => ['onclick' => 'viewWorkExperience("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Voluntary Work',
                            'content' => '<div id="voluntary-work"></div>',
                            'headerOptions' => ['onclick' => 'viewVoluntaryWork("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Learning & Development',
                            'content' => '<div id="training"></div>',
                            'headerOptions' => ['onclick' => 'viewTraining("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Other Information',
                            'content' => '<div id="other-information"></div>',
                            'headerOptions' => ['onclick' => 'viewOtherInformation("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'Questions',
                            'content' => '<div id="questions"></div>',
                            'headerOptions' => ['onclick' => 'viewQuestions("'.$model->emp_id.'")'],
                        ],
                        [
                            'label' => 'References',
                            'content' => '<div id="references"></div>',
                            'headerOptions' => ['onclick' => 'viewReferences("'.$model->emp_id.'")'],
                        ],
                    ],
                ]); ?>
            </div>
    </div>
</div>
<style>
    table.inverted-table th{
        background-color: #F4F4F5;  
        font-weight: normal; 
        border: 1px solid #ECF0F5 !important;
        text-align: right;
    }
    table.inverted-table td{
        font-weight: bolder !important;
        border: 1px solid #ECF0F5 !important;
    }
</style>
<?php
    $script = '
        $(document).ready(function(){
            
        });

        function viewPersonalInformation(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-personal-information']).'?id=" + id,
                beforeSend: function(){
                    $("#personal-information").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#personal-information").empty();
                    $("#personal-information").hide();
                    $("#personal-information").fadeIn("slow");
                    $("#personal-information").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewFamilyBackground(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-family-background']).'?id=" + id,
                beforeSend: function(){
                    $("#family-background").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#family-background").empty();
                    $("#family-background").hide();
                    $("#family-background").fadeIn("slow");
                    $("#family-background").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewEducationalBackground(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-educational-background']).'?id=" + id,
                beforeSend: function(){
                    $("#educational-background").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#educational-background").empty();
                    $("#educational-background").hide();
                    $("#educational-background").fadeIn("slow");
                    $("#educational-background").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewEligibility(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-eligibility']).'?id=" + id,
                beforeSend: function(){
                    $("#eligibility").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#eligibility").empty();
                    $("#eligibility").hide();
                    $("#eligibility").fadeIn("slow");
                    $("#eligibility").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewWorkExperience(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-work-experience']).'?id=" + id,
                beforeSend: function(){
                    $("#work-experience").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#work-experience").empty();
                    $("#work-experience").hide();
                    $("#work-experience").fadeIn("slow");
                    $("#work-experience").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewVoluntaryWork(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-voluntary-work']).'?id=" + id,
                beforeSend: function(){
                    $("#voluntary-work").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#voluntary-work").empty();
                    $("#voluntary-work").hide();
                    $("#voluntary-work").fadeIn("slow");
                    $("#voluntary-work").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewTraining(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-training']).'?id=" + id,
                beforeSend: function(){
                    $("#training").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#training").empty();
                    $("#training").hide();
                    $("#training").fadeIn("slow");
                    $("#training").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewOtherInformation(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-other-information']).'?id=" + id,
                beforeSend: function(){
                    $("#other-information").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#other-information").empty();
                    $("#other-information").hide();
                    $("#other-information").fadeIn("slow");
                    $("#other-information").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewQuestions(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-question']).'?id=" + id,
                beforeSend: function(){
                    $("#questions").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#questions").empty();
                    $("#questions").hide();
                    $("#questions").fadeIn("slow");
                    $("#questions").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewReferences(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-reference']).'?id=" + id,
                beforeSend: function(){
                    $("#references").html("<div class=\"text-center\" style=\"height: calc(100vh - 297px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#references").empty();
                    $("#references").hide();
                    $("#references").fadeIn("slow");
                    $("#references").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>

<?php
$script = <<< JS
    $(function() {
        $('a[data-toggle="tab"]').on('click', function (e) {
            localStorage.setItem('staffPdsLastTab', $(e.target).attr('href'));
        });

        //go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('staffPdsLastTab');

        if (lastTab) {
            $('a[href="'+lastTab+'"]').click();
        }
    });
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>