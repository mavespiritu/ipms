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
                    <?php if(!empty($agencies['subChapterOutcomes'])){ ?>
                        <?php $m = 0; ?>
                        <?php foreach($agencies['subChapterOutcomes'] as $subChapterOutcome => $subChapterOutcomes){ ?>
                            <tr style="font-weight: bolder;">
                                <td align=right>&nbsp;</td>
                                <td colspan=3><?= $smallCaps[$m] ?>. <?= $subChapterOutcome ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['allocationAsOfReportingPeriod'], 2) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['releasesAsOfReportingPeriod'], 2) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['obligationsAsOfReportingPeriod'], 2) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['expendituresAsOfReportingPeriod'], 2) ?></td>
                                <td align=right><?= $subChapterOutcomes['content']['allocationAsOfReportingPeriod'] > 0 ? number_format(($subChapterOutcomes['content']['releasesAsOfReportingPeriod'] / $subChapterOutcomes['content']['allocationAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                <td align=right><?= $subChapterOutcomes['content']['releasesAsOfReportingPeriod'] > 0 ? number_format(($subChapterOutcomes['content']['expendituresAsOfReportingPeriod'] / $subChapterOutcomes['content']['releasesAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['physicalTargetAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['physicalActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= count($subChapterOutcomes) > 0 ? number_format(($subChapterOutcomes['content']['slippage']/count($subChapterOutcomes)), 2) : number_format(0, 2) ?></td>
                                <td align=right><?= $subChapterOutcomes['content']['physicalTargetAsOfReportingPeriod'] > 0 ? number_format(($subChapterOutcomes['content']['physicalActualAsOfReportingPeriod'] / $subChapterOutcomes['content']['physicalTargetAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['malesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['malesEmployedTargetAsOfReportingPeriod'] + $subChapterOutcomes['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['malesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['malesEmployedActualAsOfReportingPeriod'] + $subChapterOutcomes['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['beneficiariesTargetAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['maleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['maleBeneficiariesActualAsOfReportingPeriod'] + $subChapterOutcomes['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['completed'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['behindSchedule'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['onSchedule'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['aheadOnSchedule'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['notYetStarted'], 0) ?></td>
                                <td align=right><?= number_format($subChapterOutcomes['content']['completed'] + $subChapterOutcomes['content']['behindSchedule'] + $subChapterOutcomes['content']['onSchedule'] + $subChapterOutcomes['content']['aheadOnSchedule'] + $subChapterOutcomes['content']['notYetStarted'], 0) ?></td>
                            </tr>
                            <?php if(!empty($subChapterOutcomes['chapterOutcomes'])){ ?>
                                <?php $j = 0; ?>
                                <?php foreach($subChapterOutcomes['chapterOutcomes'] as $chapterOutcome => $chapterOutcomes){ ?>
                                    <tr style="font-weight: bolder;">
                                        <td align=right>&nbsp;</td>
                                        <td align=right>&nbsp;</td>
                                        <td colspan=2><?= $numbers[$j] ?>. <?= $chapterOutcome ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['allocationAsOfReportingPeriod'], 2) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['releasesAsOfReportingPeriod'], 2) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['obligationsAsOfReportingPeriod'], 2) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['expendituresAsOfReportingPeriod'], 2) ?></td>
                                        <td align=right><?= $chapterOutcomes['content']['allocationAsOfReportingPeriod'] > 0 ? number_format(($chapterOutcomes['content']['releasesAsOfReportingPeriod'] / $chapterOutcomes['content']['allocationAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                        <td align=right><?= $chapterOutcomes['content']['releasesAsOfReportingPeriod'] > 0 ? number_format(($chapterOutcomes['content']['expendituresAsOfReportingPeriod'] / $chapterOutcomes['content']['releasesAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['physicalTargetAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['physicalActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= count($chapterOutcomes) > 0 ? number_format(($chapterOutcomes['content']['slippage']/count($chapterOutcomes)), 2) : number_format(0, 2) ?></td>
                                        <td align=right><?= $chapterOutcomes['content']['physicalTargetAsOfReportingPeriod'] > 0 ? number_format(($chapterOutcomes['content']['physicalActualAsOfReportingPeriod'] / $chapterOutcomes['content']['physicalTargetAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['malesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['malesEmployedTargetAsOfReportingPeriod'] + $chapterOutcomes['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['malesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['malesEmployedActualAsOfReportingPeriod'] + $chapterOutcomes['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['beneficiariesTargetAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['maleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['maleBeneficiariesActualAsOfReportingPeriod'] + $chapterOutcomes['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['completed'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['behindSchedule'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['onSchedule'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['aheadOnSchedule'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['notYetStarted'], 0) ?></td>
                                        <td align=right><?= number_format($chapterOutcomes['content']['completed'] + $chapterOutcomes['content']['behindSchedule'] + $chapterOutcomes['content']['onSchedule'] + $chapterOutcomes['content']['aheadOnSchedule'] + $chapterOutcomes['content']['notYetStarted'], 0) ?></td>
                                    </tr>
                                    <?php if(!empty($chapterOutcomes['chapters'])){ ?>
                                        <?php $k = 0; ?>
                                        <?php foreach($chapterOutcomes['chapters'] as $chapter => $chapters){ ?>
                                            <tr>
                                                <td align=right>&nbsp;</td>
                                                <td align=right>&nbsp;</td>
                                                <td align=right>&nbsp;</td>
                                                <td><?= $numbers[$j].'.'.$numbers[$k] ?>. <?= $chapter ?></td>
                                                <td align=right><?= number_format($chapters['content']['allocationAsOfReportingPeriod'], 2) ?></td>
                                                <td align=right><?= number_format($chapters['content']['releasesAsOfReportingPeriod'], 2) ?></td>
                                                <td align=right><?= number_format($chapters['content']['obligationsAsOfReportingPeriod'], 2) ?></td>
                                                <td align=right><?= number_format($chapters['content']['expendituresAsOfReportingPeriod'], 2) ?></td>
                                                <td align=right><?= $chapters['content']['allocationAsOfReportingPeriod'] > 0 ? number_format(($chapters['content']['releasesAsOfReportingPeriod'] / $chapters['content']['allocationAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                                <td align=right><?= $chapters['content']['releasesAsOfReportingPeriod'] > 0 ? number_format(($chapters['content']['expendituresAsOfReportingPeriod'] / $chapters['content']['releasesAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                                <td align=right><?= number_format($chapters['content']['physicalTargetAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['physicalActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= count($chapters) > 0 ? number_format(($chapters['content']['slippage']/count($chapters)), 2) : number_format(0, 2) ?></td>
                                                <td align=right><?= $chapters['content']['physicalTargetAsOfReportingPeriod'] > 0 ? number_format(($chapters['content']['physicalActualAsOfReportingPeriod'] / $chapters['content']['physicalTargetAsOfReportingPeriod']) * 100, 2) : number_format(0, 2) ?></td>
                                                <td align=right><?= number_format($chapters['content']['malesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['malesEmployedTargetAsOfReportingPeriod'] + $chapters['content']['femalesEmployedTargetAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['malesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['malesEmployedActualAsOfReportingPeriod'] + $chapters['content']['femalesEmployedActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['beneficiariesTargetAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['maleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['maleBeneficiariesActualAsOfReportingPeriod'] + $chapters['content']['femaleBeneficiariesActualAsOfReportingPeriod'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['completed'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['behindSchedule'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['onSchedule'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['aheadOnSchedule'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['notYetStarted'], 0) ?></td>
                                                <td align=right><?= number_format($chapters['content']['completed'] + $chapters['content']['behindSchedule'] + $chapters['content']['onSchedule'] + $chapters['content']['aheadOnSchedule'] + $chapters['content']['notYetStarted'], 0) ?></td>
                                            </tr>
                                            <?php $k++ ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php $j++ ?>
                                <?php } ?>
                            <?php } ?>
                            <?php $m++ ?>
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