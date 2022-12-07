<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\View;
use yii\bootstrap\ButtonDropdown;
use faryshta\disableSubmitButtons\Asset as DisableButtonAsset;
DisableButtonAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\modules\rpmes\models\DueDateSearch */
/* @var $form yii\widgets\ActiveForm */

?>
<style>
#financialChart {
  width: 100%;
  height: 500px;
}
#employmentChart {
  width: 100%;
  height: 500px;
}
</style>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<div id="graphs-table" style="height: 300px; overflow-y: scroll;">
    <hr>
    <h3 align=center>Project Breakdown per Sector</h3>
    <table class="table table-condensed table-bordered table-striped table-hover table-condensed table-responsive" style="width: 100%; height: 200px;" >
        <thead>
            <tr>
                <td rowspan=2 align=center><b>Sectors</b></td>
                <td colspan=3 align=center><b>Project Status</b></td>
                <td rowspan=2 align=center><b>Total</b></td>
            </tr>
            <tr>
                <td align=center><b>Completed</b></td>
                <td align=center><b>Ongoing</b></td>
                <td align=center><b>Not Yet Started</b></td>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($projectStatus)){ ?>
                <?php foreach($projectStatus as $project){ ?>
                    <tr style="font-weight: bolder;">
                        <td><?= $project['sectorTitle'] ?></td>
                        <td align=center><?= intval($project['completed']) ?></td>
                        <td align=center><?= intval($project['behindSchedule']) + intval($project['onSchedule']) + intval($project['aheadOnSchedule']) ?></td>
                        <td align=center><?= intval($project['notYetStartedWithTarget']) +  intval($project['notYetStartedWithNoTarget']) ?></td>
                        <td align=center><?= intval($project['completed']) + intval($project['behindSchedule']) + intval($project['onSchedule']) + intval($project['aheadOnSchedule']) + intval($project['notYetStartedWithTarget']) +  intval($project['notYetStartedWithNoTarget']) ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    <hr>
    <h3 align=center>Financial Breakdown by Category</h3>
    <div id="financialChart"></div>
    <hr>
    <h3 align=center>Employment Generated by Category</h3>
    <div id="employmentChart"></div>

</div>
<?php
    $script = "
        $(document).ready(function(){
            $('.graphs-table').freezeTable({
                'scrollable': true,
            });
        });

        am5.ready(function() {

            var root = am5.Root.new('financialChart');
            
            root.setThemes([
              am5themes_Animated.new(root)
            ]);
            
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
              radius: am5.percent(90),
              innerRadius: am5.percent(50),
              layout: root.horizontalLayout
            }));
            
            var series = chart.series.push(am5percent.PieSeries.new(root, {
              name: 'Series',
              valueField: 'value',
              categoryField: 'category'
            }));
            
            series.data.setAll([".$script."]);
            
            series.labels.template.set('visible', false);
            series.ticks.template.set('visible', false);
            
            series.slices.template.set('strokeOpacity', 0);
            series.slices.template.set('fillGradient', am5.RadialGradient.new(root, {
              stops: [{
                brighten: -0.8
              }, {
                brighten: -0.8
              }, {
                brighten: -0.5
              }, {
                brighten: 0
              }, {
                brighten: -0.5
              }]
            }));

            var legend = chart.children.push(am5.Legend.new(root, {
              centerY: am5.percent(50),
              y: am5.percent(50),
              layout: root.verticalLayout
            }));
            
            legend.valueLabels.template.setAll({ textAlign: 'right' })
            
            legend.labels.template.setAll({ 
              maxWidth: 140,
              width: 140,
              oversizedBehavior: 'wrap'
            });
            
            legend.data.setAll(series.dataItems);
            
            series.appear(1000, 100);
        });
    ";

    $this->registerJs($script, View::POS_END);
?>
