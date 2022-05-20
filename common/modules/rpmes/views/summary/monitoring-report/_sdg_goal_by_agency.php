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
<div class="pull-left">
    <?= ButtonDropdown::widget([
        'label' => '<i class="fa fa-download"></i> Export',
        'encodeLabel' => false,
        'options' => ['class' => 'btn btn-success btn-sm'],
        'dropdown' => [
            'items' => [
                ['label' => 'Excel', 'encodeLabel' => false, 'url' => Url::to(['/rpmes/summary/download-monitoring-report', 'type' => 'excel', 'year' => $model->year, 'agency_id' => $model->agency_id, 'model' => json_encode($model)])],
                ['label' => 'PDF', 'encodeLabel' => false, 'url' => Url::to(['/rpmes/summary/download-monitoring-report', 'type' => 'pdf', 'year' => $model->year, 'agency_id' => $model->agency_id, 'model' => json_encode($model)])],
            ],
        ],
    ]); ?>
    <?= Html::button('<i class="fa fa-print"></i> Print', ['onClick' => 'printSummaryReport("'.$model->year.'","'.$model->quarter.'","'.$model->fund_source_id.'","'.$model->agency_id.'","'.$model->region_id.'","'.$model->province_id.'","'.$model->citymun_id.'","'.$model->sector_id.'","'.$model->grouping.'")', 'class' => 'btn btn-danger btn-sm']) ?>
</div>
<div class="clearfix"></div>
<br>
<div class="summary-monitoring-report-table" style="min-height: 600px;">
    <table class="table table-condensed table-bordered table-striped table-hover table-condensed table-responsive" cellspacing="0" style="min-width: 3000px;">
        <thead>
            <tr>
                <td colspan=4 rowspan=3 align=center><b>Project Details</b></td>
                <td colspan=6 align=center><b>Financial Status of Reporting Period</b></td>
                <td colspan=4 align=center><b>Physical Status as of Reporting Period</b></td>
                <td colspan=6 align=center><b>Number of Persons Employed as of Reporting Period</b></td>
                <td colspan=4 align=center><b>Number of Beneficiaries as of Reporting Period</b></td>
                <td colspan=6 align=center><b>Project Implementation Status</b></td>
            </tr>
            <tr>
                <td rowspan=2 align=center><b>Allocation</b></td>
                <td rowspan=2 align=center><b>Releases</b></td>
                <td rowspan=2 align=center><b>Obligation</b></td>
                <td rowspan=2 align=center><b>Disbursement</b></td>
                <td rowspan=2 align=center><b>Funding Support (%)</b></td>
                <td rowspan=2 align=center><b>Fund Utilization (%)</b></td>
                <td rowspan=2 align=center><b>Target as of Reporting Period</b></td>
                <td rowspan=2 align=center><b>Actual Accomplishment as of Reporting Period</b></td>
                <td rowspan=2 align=center><b>Slippage as of Reporting Period (%)</b></td>
                <td rowspan=2 align=center><b>Performance as of Reporting Period</b></td>
                <td colspan=3 align=center><b>Target Persons Employed as of Reporting Period</b></td>
                <td colspan=3 align=center><b>Actual Person Employed as of Reporting Period</b></td>
                <td align=center><b>Target Beneficiaries as of Reporting Period</b></td>
                <td colspan=3 align=center><b>Actual Number as of Reporting Period</b></td>
                <td rowspan=2 align=center><b>Completed</b></td>
                <td colspan=3 align=center><b>Ongoing</b></td>
                <td rowspan=2 align=center><b>Not yet started</b></td>
                <td rowspan=2 align=center><b>Total</b></td>
            </tr>
            <tr>
                <?php if($genders){ ?>
                    <?php foreach($genders as $g => $gender){ ?>
                        <td align=center><b><?= $gender ?></b></td>
                    <?php } ?>
                <?php } ?>
                <td align=center><b>Total</b></td>
                <?php if($genders){ ?>
                    <?php foreach($genders as $g => $gender){ ?>
                        <td align=center><b><?= $gender ?></b></td>
                    <?php } ?>
                <?php } ?>
                <td align=center><b>Total</b></td>
                <td align=center><b>Total</b></td>
                <?php if($genders){ ?>
                    <?php foreach($genders as $g => $gender){ ?>
                        <td align=center><b><?= $gender ?></b></td>
                    <?php } ?>
                <?php } ?>
                <td align=center><b>Total</b></td>
                <td align=center><b>Behind Schedule</b></td>
                <td align=center><b>On-time</b></td>
                <td align=center><b>Ahead of Schedule</b></td>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($data)){ ?>
            <?php $i = 0; ?>
            <?php foreach($data as $agency => $agencies){ ?>
                    <tr style="font-weight: bolder;">
                        <td colspan=4><?= $bigCaps[$i] ?>. <?= $agency ?></td>
                        <td align=right><?= number_format($agencies['content']['allocationAsOfReportingPeriod'], 2) ?></td>
                        <td align=right><?= number_format($agencies['content']['releasesAsOfReportingPeriod'], 2) ?></td>
                        <td align=right><?= number_format($agencies['content']['obligationsAsOfReportingPeriod'], 2) ?></td>
                        <td align=right><?= number_format($agencies['content']['expendituresAsOfReportingPeriod'], 2) ?></td>
                        <td align=right><?= $agencies['content']['allocationAsOfReportingPeriod'] > 0 ? number_format(($agencies['content']['releasesAsOfReportingPeriod'] / $agencies['content']['allocationAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                        <td align=right><?= $agencies['content']['releasesAsOfReportingPeriod'] > 0 ? number_format(($agencies['content']['expendituresAsOfReportingPeriod'] / $agencies['content']['releasesAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                        <td align=right><?= number_format($agencies['content']['physicalTargetAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['physicalActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= count($agencies) > 0 ? number_format(($agencies['content']['slippage']/count($agencies)), 2) : number_format(0, 2) ?></td>
                        <td align=right><?= $agencies['content']['physicalTargetAsOfReportingPeriod'] > 0 ? number_format(($agencies['content']['physicalActualAsOfReportingPeriod'] / $agencies['content']['physicalTargetAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                        <td align=right><?= number_format($agencies['content']['malesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['malesEmployedTargetAsOfReportingPeriod'] + $agencies['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['malesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['malesEmployedActualAsOfReportingPeriod'] + $agencies['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['beneficiariesTargetAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['maleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['maleBeneficiariesActualAsOfReportingPeriod'] + $agencies['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['completed'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['behindSchedule'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['onSchedule'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['aheadOnSchedule'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['notYetStarted'], 0) ?></td>
                        <td align=right><?= number_format($agencies['content']['completed'] + $agencies['content']['behindSchedule'] + $agencies['content']['onSchedule'] + $agencies['content']['aheadOnSchedule'] + $agencies['content']['notYetStarted'], 0) ?></td>
                    </tr>
                <?php if(!empty($agencies['sdgGoals'])){ ?>
                    <?php $j = 0; ?>
                    <?php foreach($agencies['sdgGoals'] as $sdgGoal => $sdgGoals){ ?>
                        <tr>
                            <td align=right>&nbsp;</td>
                            <td colspan=3><?= $smallCaps[$j] ?>. <?= $sdgGoal ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['allocationAsOfReportingPeriod'], 2) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['releasesAsOfReportingPeriod'], 2) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['obligationsAsOfReportingPeriod'], 2) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['expendituresAsOfReportingPeriod'], 2) ?></td>
                            <td align=right><?= $sdgGoals['content']['allocationAsOfReportingPeriod'] > 0 ? number_format(($sdgGoals['content']['releasesAsOfReportingPeriod'] / $sdgGoals['content']['allocationAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                            <td align=right><?= $sdgGoals['content']['releasesAsOfReportingPeriod'] > 0 ? number_format(($sdgGoals['content']['expendituresAsOfReportingPeriod'] / $sdgGoals['content']['releasesAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['physicalTargetAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['physicalActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= count($sdgGoals) > 0 ? number_format(($sdgGoals['content']['slippage']/count($sdgGoals)), 2) : number_format(0, 2) ?></td>
                            <td align=right><?= $sdgGoals['content']['physicalTargetAsOfReportingPeriod'] > 0 ? number_format(($sdgGoals['content']['physicalActualAsOfReportingPeriod'] / $sdgGoals['content']['physicalTargetAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['malesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['malesEmployedTargetAsOfReportingPeriod'] + $sdgGoals['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['malesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['malesEmployedActualAsOfReportingPeriod'] + $sdgGoals['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['beneficiariesTargetAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['maleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['maleBeneficiariesActualAsOfReportingPeriod'] + $sdgGoals['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['completed'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['behindSchedule'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['onSchedule'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['aheadOnSchedule'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['notYetStarted'], 0) ?></td>
                            <td align=right><?= number_format($sdgGoals['content']['completed'] + $sdgGoals['content']['behindSchedule'] + $sdgGoals['content']['onSchedule'] + $sdgGoals['content']['aheadOnSchedule'] + $sdgGoals['content']['notYetStarted'], 0) ?></td>
                        </tr>
                        <?php $j++ ?>
                    <?php } ?>
                <?php } ?>
                <?php $i++ ?>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php
    $script = '
        $(document).ready(function(){
            $(".summary-monitoring-report-table").freezeTable({
                "scrollable": true,
            });
        });
    ';

    $this->registerJs($script, View::POS_END);
?>