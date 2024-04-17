<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\web\View;
use frontend\assets\AppAsset;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'Home';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$asset = AppAsset::register($this);
?>
<div class="pds-view">
    <div class="row">
        <div class="col-md-9 col-xs-12">
            <div class="box box-solid">
                <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                    <h4><b>Good <?= date("A") == 'AM' ? 'morning' : 'afternoon' ?>, <?= ucwords(strtolower(Yii::$app->user->identity->userinfo->FIRST_M)) ?>!</b><br>
                        <small>Today is <?= date("l, F j, Y") ?> <span id="realtime-clock"></span></small>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 0.9em;"><b>Total VL</b>
                                <div id="vl-credits"></div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 0.9em;"><b>Total SL</b>
                                <div id="sl-credits"></div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 0.9em;"><b>Total SPL</b>
                                <div id="wl-credits"></div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 0.9em;"><b>Leave Credits Monetary Amount</b></p>
                                <div id="monetary-amount"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>Birthday Celebrants</b></p>
                            <div id="birthday-celebrants"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>Who's out</b></p>
                            <div style="min-height: calc(100vh - 500px); max-height: 34vh; overflow-y: auto; padding-right: 20px;">
                                <p class="text-center">Coming soon.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>My Weekly DTR</b> 
                                <span class="pull-right">
                                    <?php // Html::a('View More', ['#'], ['class' => 'btn btn-xs bg-navy text-bold']) ?>
                                </span>
                            </p>
                            <div id="weekly-dtr"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="box box-solid">
                <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                    <div class="calendar" id="calendar"></div>
                    <br>
                    <p style="font-size: 1.1em;"><b>Upcoming Holidays</b></p>
                    <div id="holidays"></div>
                    <div style="min-height: calc(100vh - 533px); max-height: 40vh; overflow-y: auto; padding-right: 20px;">
                    <?php if($holidays){ ?>
                        <ul class="products-list product-list-in-box">
                        <?php foreach($holidays as $holiday){ ?>
                            <li class="item">
                                <div class="product-info" style="margin-left: 0;">
                                    <?= $holiday->holiday_name ?>
                                    <span class="product-description">
                                        <?= date("F j", strtotime($holiday->holiday_date)) ?>
                                    </span>
                                </div>
                            </li>
                        <?php } ?>
                        </ul>
                    <?php }else{ ?>
                        <p class="text-center">No holidays found.</p>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #dtr-table > thead > tr{
        background-color: #F4F4F5; 
        color: black; 
    }

    #dtr-table > thead > tr > th{
        font-weight: bolder;
    }

    #calendar {
      max-width: 100%;
      margin: 0 auto;
      background-color: #F9F8FE;
    }

    #calendar-table {
      width: 100%;
      border-collapse: collapse;
    }

    #calendar-table > tbody > tr > th {
        background-color: #00766A;
        color: white;
        padding: 10px 0;
        text-align: center;
    }

    #calendar-table > tbody > tr > td {
      padding: 10px 0;
      text-align: center;
      font-weight: bolder;
    }

    #calendar-table > tbody > tr > td:hover {
      background-color: #f0f0f0;
    }

    #calendar-table > tbody > tr > td.empty-cell {
      background-color: #f9f9f9;
    }

    #calendar-table > tbody > tr > td.current-date {
      position: relative;
    }

    #calendar-table > tbody > tr > td.current-date span {
      background-color: #FB5C58;
      border-radius: 50%;
      width: 35px;
      padding: 7px;
      position: absolute;
      color: white;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
</style>
<script>
    function generateCalendar() {
      const calendarElement = document.getElementById('calendar');
      const currentDate = new Date();
      const currentMonth = currentDate.getMonth();
      const currentYear = currentDate.getFullYear();
      const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

      let calendarHTML = '<table id="calendar-table">';
      calendarHTML += '<tr><th colspan="7" style="font-weight: bolder;">' + new Date(currentYear, currentMonth).toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) + '</th></tr>';
      calendarHTML += '<tr><th style="width: 14.28%;">S</th><th style="width: 14.28%;">M</th><th style="width: 14.28%;">T</th><th style="width: 14.28%;">W</th><th style="width: 14.28%;">T</th><th style="width: 14.28%;">F</th><th style="width: 14.28%;">S</th></tr>';

      let dayCounter = 1;
      for (let i = 0; i < 6; i++) {
        calendarHTML += '<tr>';
        for (let j = 0; j < 7; j++) {
          if (i === 0 && j < new Date(currentYear, currentMonth).getDay()) {
            calendarHTML += '<td class="empty-cell"></td>';
          } else if (dayCounter > daysInMonth) {
            break;
          } else {
            if (dayCounter === currentDate.getDate()) {
              calendarHTML += '<td class="current-date"><span>' + dayCounter + '</span></td>';
            } else {
              calendarHTML += '<td>' + dayCounter + '</td>';
            }
            dayCounter++;
          }
        }
        calendarHTML += '</tr>';
        if (dayCounter > daysInMonth) {
          break;
        }
      }

      calendarHTML += '</table>';
      calendarElement.innerHTML = calendarHTML;
    }

    // Call the function to generate the calendar
    generateCalendar();
  </script>
<?php
$this->registerJs("
function updateClock() {
    var now = moment();
    var formattedTime = now.format('hh:mm:ss A');
    document.getElementById('realtime-clock').textContent = formattedTime;
}

document.addEventListener('DOMContentLoaded', function() {
    updateClock();
    setInterval(updateClock, 1000);
});
", View::POS_END);
?>
<?php
    $script = '
        $(document).ready(function(){
            viewVlCredits();
            viewSlCredits();
            viewWlCredits();
            viewMonetaryAmount();
            viewBirthdayCelebrants();
            viewWeeklyDtr();
        });

        function viewVlCredits()
        {
            $.ajax({
                url: "'.Url::to(['/dashboard/default/vl-credits']).'",
                beforeSend: function(){
                    $("#vl-credits").html("<div class=\"text-center\" style=\"height: auto; display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#vl-credits").empty();
                    $("#vl-credits").hide();
                    $("#vl-credits").fadeIn("slow");
                    $("#vl-credits").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewSlCredits()
        {
            $.ajax({
                url: "'.Url::to(['/dashboard/default/sl-credits']).'",
                beforeSend: function(){
                    $("#sl-credits").html("<div class=\"text-center\" style=\"height: auto; display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#sl-credits").empty();
                    $("#sl-credits").hide();
                    $("#sl-credits").fadeIn("slow");
                    $("#sl-credits").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewWlCredits()
        {
            $.ajax({
                url: "'.Url::to(['/dashboard/default/wl-credits']).'",
                beforeSend: function(){
                    $("#wl-credits").html("<div class=\"text-center\" style=\"height: auto; display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#wl-credits").empty();
                    $("#wl-credits").hide();
                    $("#wl-credits").fadeIn("slow");
                    $("#wl-credits").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewMonetaryAmount()
        {
            $.ajax({
                url: "'.Url::to(['/dashboard/default/monetary-amount']).'",
                beforeSend: function(){
                    $("#monetary-amount").html("<div class=\"text-center\" style=\"height: auto; display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#monetary-amount").empty();
                    $("#monetary-amount").hide();
                    $("#monetary-amount").fadeIn("slow");
                    $("#monetary-amount").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewBirthdayCelebrants()
        {
            $.ajax({
                url: "'.Url::to(['/dashboard/default/birthday-celebrants']).'",
                beforeSend: function(){
                    $("#birthday-celebrants").html("<div class=\"text-center\" style=\"height: calc(100vh - 500px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#birthday-celebrants").empty();
                    $("#birthday-celebrants").hide();
                    $("#birthday-celebrants").fadeIn("slow");
                    $("#birthday-celebrants").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function viewWeeklyDtr()
        {
            $.ajax({
                url: "'.Url::to(['/dashboard/default/weekly-dtr']).'",
                beforeSend: function(){
                    $("#weekly-dtr").html("<div class=\"text-center\" style=\"height: calc(100vh - 500px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
                },
                success: function (data) {
                    $("#weekly-dtr").empty();
                    $("#weekly-dtr").hide();
                    $("#weekly-dtr").fadeIn("slow");
                    $("#weekly-dtr").html(data);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
    ';

    $this->registerJs($script, View::POS_END);
?>
