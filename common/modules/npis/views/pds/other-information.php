<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'My PDS Related Files';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="other-information-view">
    <h4>Other Information
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 3 of 4</span>
    </h4>
    <div class="nav-tabs-custom">
    <?= Tabs::widget([
        'id' => 'other-information-tabs',
        'items' => [
            [
                'label' => 'Special Skills and Hobbies',
                'content' => '<div id="skills"></div>',
                'headerOptions' => ['onclick' => 'viewSkills("'.$model->emp_id.'")'],
            ],
            [
                'label' => 'Non-Academic Distinctions/Recognitions',
                'content' => '<div id="recognitions"></div>',
                'headerOptions' => ['onclick' => 'viewRecognitions("'.$model->emp_id.'")'],
            ],
            [
                'label' => 'Membership in Association/Organization',
                'content' => '<div id="organizations"></div>',
                'headerOptions' => ['onclick' => 'viewOrganizations("'.$model->emp_id.'")'],
            ],
        ],
    ]); ?>
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
$script = <<< JS
    $(function() {
        $('#other-information-tabs li a').on('click', function (e) {
            localStorage.setItem('otherLastTab', $(this).attr('href'));
        });

        // Go to the latest tab, if it exists:
        var lastTab = localStorage.getItem('otherLastTab');
        if (lastTab) {
            $('a[href="'+lastTab+'"]').click();
        }
    });
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>
<?php
    $script = '
        $(document).ready(function(){

        });

        function viewSkills(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-skill']).'?id=" + id,
                beforeSend: function(){
                    $("#skills").html("<div class=\"text-center\" style=\"height: calc(100vh - 397px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#skills").empty();
                    $("#skills").hide();
                    $("#skills").fadeIn("slow");
                    $("#skills").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewRecognitions(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-recognition']).'?id=" + id,
                beforeSend: function(){
                    $("#recognitions").html("<div class=\"text-center\" style=\"height: calc(100vh - 397px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#recognitions").empty();
                    $("#recognitions").hide();
                    $("#recognitions").fadeIn("slow");
                    $("#recognitions").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewOrganizations(id)
        {
            $.ajax({
                url: "'.Url::to(['/npis/pds/view-organization']).'?id=" + id,
                beforeSend: function(){
                    $("#organizations").html("<div class=\"text-center\" style=\"height: calc(100vh - 397px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#organizations").empty();
                    $("#organizations").hide();
                    $("#organizations").fadeIn("slow");
                    $("#organizations").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>