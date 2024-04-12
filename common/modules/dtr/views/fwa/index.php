<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\dtr\models\DtrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'FWA';
$this->params['breadcrumbs'][] = 'DTR';
$this->params['breadcrumbs'][] = 'My DTR Information';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dtr-index">
    <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">FWA Time Entry Form</h3></div>
            <div class="box-body">
                <p>Note: Using this form to record time entries will consider you as working on FWA. Report to HR if accidentally used for logging time entries.</p>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="flex-center text-center">
                            <div>
                                <h4>Today is: </h4>
                                <h2><i class="fa fa-calendar"></i>  <?= date("j F Y") ?></h2>
                                <h1 style="font-size: 60px;"><span id="realtime-clock"></span></h1>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3 d-none d-md-block"></div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="am_in" class="text-left"><b>AM IN</b></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                <input type="text" id="am_in" class="form-control input-lg" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="am_out" class="text-left"><b>AM OUT</b></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                <input type="text" id="am_out" class="form-control input-lg" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="pm_in" class="text-left"><b>PM IN</b></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                <input type="text" id="pm_in" class="form-control input-lg" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="pm_out" class="text-left"><b>PM OUT</b></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                <input type="text" id="pm_out" class="form-control input-lg" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <?= $this->render('_form', [
                                            'am' => $am,
                                            'pm' => $pm,
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 d-none d-md-block"></div>
                        </div>
                    </div>
                </div>
                
            </div>
    </div>
</div>

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
       updateAmIn();
       updateAmOut();
       updatePmIn();
       updatePmOut();
    });

    function updateAmIn(){
        $.ajax({
            url: "'.Url::to(['/dtr/fwa/am-in']).'",
            success: function (data) {
                $("#am_in").empty();
                $("#am_in").val(data);
            },
            error: function (err) {
                console.log(err);
            }
        }); 
    }

    function updateAmOut(){
        $.ajax({
            url: "'.Url::to(['/dtr/fwa/am-out']).'",
            success: function (data) {
                $("#am_out").empty();
                $("#am_out").val(data);
            },
            error: function (err) {
                console.log(err);
            }
        }); 
    }

    function updatePmIn(){
        $.ajax({
            url: "'.Url::to(['/dtr/fwa/pm-in']).'",
            success: function (data) {
                $("#pm_in").empty();
                $("#pm_in").val(data);
            },
            error: function (err) {
                console.log(err);
            }
        }); 
    }

    function updatePmOut(){
        $.ajax({
            url: "'.Url::to(['/dtr/fwa/pm-out']).'",
            success: function (data) {
                $("#pm_out").empty();
                $("#pm_out").val(data);
            },
            error: function (err) {
                console.log(err);
            }
        }); 
    }
    ';

    $this->registerJs($script, View::POS_END);
?>