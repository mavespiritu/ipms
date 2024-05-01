<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\web\View;
use kartik\select2\Select2;
use common\models\Employee;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\npis\models\IpcrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Staff Profile';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="staff-profile-index">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Staff Profile</h3></div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Search Filters</div>
                        <div class="panel-body" style="height: calc(100vh - 316px); overflow-y: auto;">
                        <input type="checkbox" class="all-items" />&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-weight: 500;">Select All</span><br>
                            <input type="checkbox" class="basic-information-items" style="margin-left: 15px;" />&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-weight: 500;">Basic Information</span><br>
                            <?php foreach($fields['basic_information'] as $key => $filter){ ?>
                                <input type="checkbox" class="basic-information-item" name="StaffProfile[]" value="<?= $key ?>" style="margin-left: 45px;" onClick="viewStaffProfile()" />&nbsp;&nbsp;&nbsp;<?= $filter ?> <br>
                            <?php } ?>
                            <input type="checkbox" class="family-items" style="margin-left: 15px;" />&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-weight: 500;">Family Information</span><br>
                            <?php foreach($fields['family'] as $key => $filter){ ?>
                                <input type="checkbox" class="family-item" name="StaffProfile[]" value="<?= $key ?>" style="margin-left: 45px;" onClick="viewStaffProfile()" />&nbsp;&nbsp;&nbsp;<?= $filter ?> <br>
                            <?php } ?>
                            <input type="checkbox" class="profile-items" style="margin-left: 15px;" />&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-weight: 500;">Staff Profile</span><br>
                            <?php foreach($fields['profile'] as $key => $filter){ ?>
                                <input type="checkbox" class="profile-item" name="StaffProfile[]" value="<?= $key ?>" style="margin-left: 45px;" onClick="viewStaffProfile()" />&nbsp;&nbsp;&nbsp;<?= $filter ?> <br>
                            <?php } ?>
                            <input type="checkbox" class="work-related-items" style="margin-left: 15px;" />&nbsp;&nbsp;&nbsp;<span style="font-size: 14px; font-weight: 500;">Work History</span><br>
                            <?php foreach($fields['work_related'] as $key => $filter){ ?>
                                <input type="checkbox" class="work-related-item" name="StaffProfile[]" value="<?= $key ?>" style="margin-left: 45px;" onClick="viewStaffProfile()" />&nbsp;&nbsp;&nbsp;<?= $filter ?> <br>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-12">
                    <div class="row">
                        <div class="col-md-3 col-xs-12">
                            <label class="control-label">Division</label>
                            <?= Select2::widget([
                                'name' => 'division',
                                'data' => $divisions,
                                'size' => Select2::SMALL,
                                'options' => [
                                    'placeholder' => 'Select one or more',
                                    'multiple' => true
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'pluginEvents' => [
                                    'select2:select' => '
                                        function(){
                                            if (!window.programmaticChange) {
                                                viewStaffProfile();
                                            }
                                            window.programmaticChange = false; // Reset the flag
                                        }'
                    
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label class="control-label">Staff</label>
                            <?= Select2::widget([
                                'name' => 'emp_id',
                                'data' => \common\models\Employee::getAllList(),
                                'options' => [
                                    'placeholder' => 'Select one',
                                    'multiple' => false
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'pluginEvents' => [
                                    'select2:select' => '
                                        function(){
                                            if (!window.programmaticChange) {
                                                viewStaffProfile();
                                            }
                                            window.programmaticChange = false; // Reset the flag
                                        }'
                    
                                ]
                            ]); ?>
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label class="control-label">Work Status</label>
                            <?= Select2::widget([
                                'name' => 'work_status',
                                'data' => [
                                    'Active' => 'Active',
                                    'Inactive' => 'Inactive',
                                ],
                                'options' => [
                                    'placeholder' => 'Select one',
                                    'multiple' => false
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                'pluginEvents' => [
                                    'select2:select' => '
                                        function(){
                                            viewStaffProfile()
                                        }'
                    
                                ]
                            ]); ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="flex-center" style="height: calc(100vh - 330px); padding: 10px auto; overflow: auto;">
                                <div id="staff-profile-information">
                                        <h3 style="color: gray;">Apply filters to generate information</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    $(".all-items").change(function() {
        $(".basic-information-items").prop("checked", $(this).prop("checked"));
        $(".basic-information-item").prop("checked", $(this).prop("checked"));
        $(".family-items").prop("checked", $(this).prop("checked"));
        $(".family-item").prop("checked", $(this).prop("checked"));
        $(".profile-items").prop("checked", $(this).prop("checked"));
        $(".profile-item").prop("checked", $(this).prop("checked"));
        $(".work-related-items").prop("checked", $(this).prop("checked"));
        $(".work-related-item").prop("checked", $(this).prop("checked"));

        viewStaffProfile();
    });

    $(".basic-information-items").change(function() {
        $(".basic-information-item").prop("checked", $(this).prop("checked"));
        viewStaffProfile();
    });
    $(".family-items").change(function() {
        $(".family-item").prop("checked", $(this).prop("checked"));
        viewStaffProfile();
    });
    $(".profile-items").change(function() {
        $(".profile-item").prop("checked", $(this).prop("checked"));
        viewStaffProfile();
    });
    $(".work-related-items").change(function() {
        $(".work-related-item").prop("checked", $(this).prop("checked"));
        viewStaffProfile();
    });

    function viewStaffProfile()
    {
        $.ajax({
            url: "'.Url::to(['/npis/staff-profile/']).'",
            beforeSend: function(){
                $("#staff-profile-information").html("<div class=\"text-center\" style=\"height: calc(100vh - 330px); display: flex; align-items: center; justify-content: center;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
            },
            success: function (data) {
                $("#staff-profile-information").empty();
                $("#staff-profile-information").hide();
                $("#staff-profile-information").fadeIn("slow");
                $("#staff-profile-information").html(data);
            },
            error: function (err) {
                console.log(err);
            }
        });
    }
    ';

    $this->registerJs($script, View::POS_END);
?>