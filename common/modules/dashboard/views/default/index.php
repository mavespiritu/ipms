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
                        <small>Today is <?= date("F j, Y") ?> <span id="realtime-clock"></span></small>
                    </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>Birthday Celebrants</b></p>
                            <div style="min-height: calc(100vh - 600px); max-height: 34vh; overflow-y: auto; padding-right: 20px;">
                            <?php if($celebrants){ ?>
                                <ul class="products-list product-list-in-box">
                                <?php foreach($celebrants as $celebrant){ ?>
                                    <?php $base64Image = base64_encode($celebrant->picture); ?>
                                    <li class="item">
                                        <div class="product-img" style="position: relative;">
                                            <?= '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Image" class="direct-chat-img "style="border-radius: 50%; width: 40px; height: 40px;">' ?>
                                            <?php if(date("d") == date("d", strtotime($celebrant->birth_date))){ ?>
                                                <img src="<?= $asset->baseUrl.'/images/birthday-hat.png' ?>" alt="Birthday Hat" style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 20px; height: auto;">
                                            <?php } ?>
                                        </div>
                                        <div class="product-info">
                                            <?= $celebrant->fname.' '.$celebrant->lname ?>
                                            <?= date("F j", strtotime($celebrant->birth_date)) == date("F j") ? '<span class="label label-success pull-right">Today</span>' : '<span class="pull-right">'.date("F j", strtotime($celebrant->birth_date)).'</span>' ?>
                                            <span class="product-description">
                                                <?= $celebrant->position_id ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php } ?>
                                </ul>
                            <?php }else{ ?>
                                <p class="text-center">No birthday celebrants found.</p>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>Who's out</b></p>
                            <div style="min-height: calc(100vh - 600px); max-height: 34vh; overflow-y: auto; padding-right: 20px;">
                                <p class="text-center">Coming soon.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>My Weekly DTR</b></p>
                            <div style="min-height: calc(100vh - 600px); max-height: 34vh; overflow-y: auto; font-size: 11px;">
                                <table id="dtr-table" class="table table-responsive table-bordered table-condensed">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>AM IN</th>
                                            <th>AM OUT</th>
                                            <th>PM IN</th>
                                            <th>PM OUT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(!empty($weeklyDtrs)){ ?>
                                        <?php foreach($weeklyDtrs as $dtr){ ?>
                                            <tr>
                                                <td><?= date("F j", strtotime($dtr['date'])) ?></td>
                                                <td align=center><?= $dtr['am_in'] ?></td>
                                                <td align=center><?= $dtr['am_out'] ?></td>
                                                <td align=center><?= $dtr['pm_in'] ?></td>
                                                <td align=center><?= $dtr['pm_out'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <table id="dtr-table" class="table table-responsive table-bordered table-condensed">
                                    <tr>
                                        <th style="width: 50%; background-color: #F4F4F5; font-weight: bolder;">HRS TO RENDER:</th>
                                        <td align=center><?= $hrsToRender[0]['total_hours'].' ('.$hrsToRenderInHrs.')' ?></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 50%; background-color: #F4F4F5; font-weight: bolder;">HRS RENDERED:</th>
                                        <td align=center><?= $total.' ('.$totalInHrs.')' ?></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 50%; background-color: #F4F4F5; font-weight: bolder;">HRS TO GO:</th>
                                        <td align=center><?= $hrsToGo.' ('.$hrsToGoInHours.')' ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="box box-solid">
                        <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 20px 20px 20px 20px;">
                            <p style="font-size: 1.1em;"><b>Who's out</b></p>
                            <div style="min-height: calc(100vh - 770px); max-height: 10vh; overflow-y: auto; padding-right: 20px;">
                                <p class="text-center">Coming soon.</p>
                            </div>
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
                    <div style="min-height: calc(100vh - 550px); max-height: 40vh; overflow-y: auto; padding-right: 20px;">
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
