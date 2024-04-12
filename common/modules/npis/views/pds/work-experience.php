<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\web\View;
use yii\grid\GridView;
use frontend\assets\AppAsset;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
$asset = AppAsset::register($this);

?>
<?php 
    $cloneDataProvider = clone $dataProvider;
    $cloneDataProvider->pagination = false;
    if($cloneDataProvider->models){
        foreach($cloneDataProvider->models as $idx => $model){

            Modal::begin([
                'id' => 'update-appointment-modal-'.($idx + 1),
                'size' => 'modal-md',
                'header' => '<div id="update-appointment-modal-'.($idx + 1).'-header"><h4>Upload Appointment</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-appointment-modal-'.($idx + 1).'-content"></div>';
            Modal::end();

            Modal::begin([
                'id' => 'update-duty-assumption-modal-'.($idx + 1),
                'size' => 'modal-md',
                'header' => '<div id="update-duty-assumption-modal-'.($idx + 1).'-header"><h4>Upload Assumption of Duty</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-duty-assumption-modal-'.($idx + 1).'-content"></div>';
            Modal::end();

            Modal::begin([
                'id' => 'update-position-description-modal-'.($idx + 1),
                'size' => 'modal-md',
                'header' => '<div id="update-position-description-modal-'.($idx + 1).'-header"><h4>Upload Position Description</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-position-description-modal-'.($idx + 1).'-content"></div>';
            Modal::end();

            Modal::begin([
                'id' => 'update-office-oath-modal-'.($idx + 1),
                'size' => 'modal-md',
                'header' => '<div id="update-office-oath-modal-'.($idx + 1).'-header"><h4>Upload Oath of Office</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-office-oath-modal-'.($idx + 1).'-content"></div>';
            Modal::end();

            Modal::begin([
                'id' => 'update-nosa-nosi-modal-'.($idx + 1),
                'size' => 'modal-md',
                'header' => '<div id="update-nosa-nosi-modal-'.($idx + 1).'-header"><h4>Upload NOSA/NOSI</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-nosa-nosi-modal-'.($idx + 1).'-content"></div>';
            Modal::end();
        }    
    } 
?>
<div>
    <h4>Work Experience
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 2 of 4</span>
    </h4>

    <div id="alert" class="alert" role="alert" style="display: none;"></div>
    <?php
        if(Yii::$app->session->hasFlash('success')):?>
            <div class="alert alert-success" role="alert">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif;
        if(Yii::$app->session->hasFlash('error')):?>
            <div class="alert alert-danger" role="alert">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif;
    ?>

    <?php Pjax::begin([
        'id' => 'work-experience-grid-pjax', 
        'enablePushState' => false, 
        'enableReplaceState' => false,
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'class' => 'table-responsive'
        ],
        'tableOptions' => [
            'class' => 'table table-bordered table-hover',
            'id' => 'work-experience-table'
        ],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
            ],
            [
                'attribute' => 'date_start',
                'header' => 'FROM',
                'headerOptions' => [
                    'style' => 'font-weight: bolder !important;'
                ],
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'date_end',
                'header' => 'TO',
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            
            [
                'attribute' => 'position',
                'header' => 'POSITION TITLE',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'agency',
                'header' => 'DEPARTMENT/<br>AGENCY/<br>OFFICE/<br>COMPANY',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'monthly_salary',
                'header' => 'MONTHLY<br>SALARY',
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
            ],
            [
                'attribute' => 'grade',
                'header' => 'SALARY/<br>JOB/<br>PAY GRADE',
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'value' => function($model){
                    return $model->grade.'-'.$model->step;
                }
            ],
            [
                'attribute' => 'appointment',
                'header' => 'STATUS OF<br>APPOINTMENT',
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'type',
                'header' => 'GOV\'T<br>SERVICE<br>(Y/N)',
                'headerOptions' => [
                    'style' => 'width: 5%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
            ],
            [
                'header' => 'filename',
                'header' => 'APPOINTMENT',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'stext-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    $html = Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<span class="pull-right">'.Html::a('<i class="fa fa-upload"></i>', '#', [
                        'class' => 'update-appointment-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-appointment-modal-'.$index,
                        'data-url' => Url::to(['/npis/pds/update-appointment', 
                            'emp_id' => $model->emp_id, 
                            'agency' => $model->agency, 
                            'position' => $model->position, 
                            'appointment' => $model->appointment, 
                            'grade' => $model->grade, 
                            'monthly_salary' => $model->monthly_salary, 
                            'date_start' => $model->date_start, 
                            'step' => $model->step, 
                            'idx' => $index]),
                    ]).'</span>
                    <span class="clearfix"></span>' : '';

                    $html .= $model->appointmentPath;

                    return $html;
                }
            ],
            [
                'attribute' => 'filename2',
                'header' => 'ASSUMPTION OF DUTY',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    $html = Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<span class="pull-right">'.Html::a('<i class="fa fa-upload"></i>', '#', [
                        'class' => 'update-duty-assumption-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-duty-assumption-modal-'.$index,
                        'data-url' => Url::to(['/npis/pds/update-duty-assumption', 
                            'emp_id' => $model->emp_id, 
                            'agency' => $model->agency, 
                            'position' => $model->position, 
                            'appointment' => $model->appointment, 
                            'grade' => $model->grade, 
                            'monthly_salary' => $model->monthly_salary, 
                            'date_start' => $model->date_start, 
                            'step' => $model->step, 
                            'idx' => $index]),
                    ]).'</span>
                    <span class="clearfix"></span>' : '';

                    $html .= $model->dutyAssumptionPath;

                    return $html;
                }
            ],
            [
                'attribute' => 'filename3',
                'header' => 'POSITION<br>DESCRIPTION',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    $html = Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<span class="pull-right">'.Html::a('<i class="fa fa-upload"></i>', '#', [
                        'class' => 'update-position-description-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-position-description-modal-'.$index,
                        'data-url' => Url::to(['/npis/pds/update-position-description', 
                            'emp_id' => $model->emp_id, 
                            'agency' => $model->agency, 
                            'position' => $model->position, 
                            'appointment' => $model->appointment, 
                            'grade' => $model->grade, 
                            'monthly_salary' => $model->monthly_salary, 
                            'date_start' => $model->date_start, 
                            'step' => $model->step, 
                            'idx' => $index]),
                    ]).'</span>
                    <span class="clearfix"></span>' : '';

                    $html .= $model->positionDescriptionPath;

                    return $html;
                }
            ],
            [
                'attribute' => 'filename4',
                'header' => 'OATH OF <br>OFFICE',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    $html = Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<span class="pull-right">'.Html::a('<i class="fa fa-upload"></i>', '#', [
                        'class' => 'update-office-oath-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-office-oath-modal-'.$index,
                        'data-url' => Url::to(['/npis/pds/update-office-oath', 
                            'emp_id' => $model->emp_id, 
                            'agency' => $model->agency, 
                            'position' => $model->position, 
                            'appointment' => $model->appointment, 
                            'grade' => $model->grade, 
                            'monthly_salary' => $model->monthly_salary, 
                            'date_start' => $model->date_start, 
                            'step' => $model->step, 
                            'idx' => $index]),
                    ]).'</span>
                    <span class="clearfix"></span>' : '';

                    $html .= $model->officeOathPath;

                    return $html;
                }
            ],
            [
                'attribute' => 'filename5',
                'header' => 'NOSA/NOSI',
                'headerOptions' => [
                    'style' => 'width: 10%; font-weight: bolder !important; text-align: center'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'format' => 'raw',
                'value' => function($model, $key, $index) use ($dataProvider){
                    $pagination = $dataProvider->getPagination();
                    if ($pagination !== false) {
                        // Calculate the index based on the current page and page size
                        $index = $pagination->getPage() * $pagination->pageSize + $index + 1;
                    } else {
                        // If pagination is disabled, just use the $index directly
                        $index += 1;
                    }

                    $html = Yii::$app->user->can('HR') && ($model->emp_id != Yii::$app->user->identity->userinfo->EMP_N) ? '<span class="pull-right">'.Html::a('<i class="fa fa-upload"></i>', '#', [
                        'class' => 'update-nosa-nosi-button',
                        'data-toggle' => 'modal',
                        'data-target' => '#update-nosa-nosi-modal-'.$index,
                        'data-url' => Url::to(['/npis/pds/update-nosa-nosi', 
                            'emp_id' => $model->emp_id, 
                            'agency' => $model->agency, 
                            'position' => $model->position, 
                            'appointment' => $model->appointment, 
                            'grade' => $model->grade, 
                            'monthly_salary' => $model->monthly_salary, 
                            'date_start' => $model->date_start, 
                            'step' => $model->step, 
                            'idx' => $index]),
                    ]).'</span>
                    <span class="clearfix"></span>' : '';

                    $html .= $model->nosaNosiPath;

                    return $html;
                }
            ],
        ],
    ]); ?>

<?php
        $this->registerJs('
            $(document).ready(function(){
                function initModal(modalId, contentUrl) {
                    $(modalId).modal("show").find(modalId + "-content").load(contentUrl);
                }

                $(".update-appointment-button").click(function(e){
                    e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".update-duty-assumption-button").click(function(e){
                    e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".update-position-description-button").click(function(e){
                    e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".update-office-oath-button").click(function(e){
                    e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(".update-nosa-nosi-button").click(function(e){
                    e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(document).on("pjax:success", function() {
                    
                    if (!$("#work-experience-grid-pjax").data("first-load")) {
                        return;
                    }
                    
                    $(".update-appointment-button").click(function(e){
                        e.preventDefault();
    
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Initialize modal and content
                        return false;
                    });
    
                    $(".update-duty-assumption-button").click(function(e){
                        e.preventDefault();
    
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Initialize modal and content
                        return false;
                    });
    
                    $(".update-position-description-button").click(function(e){
                        e.preventDefault();
    
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Initialize modal and content
                        return false;
                    });
    
                    $(".update-office-oath-button").click(function(e){
                        e.preventDefault();
    
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Initialize modal and content
                        return false;
                    });
    
                    $(".update-nosa-nosi-button").click(function(e){
                        e.preventDefault();
    
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Initialize modal and content
                        return false;
                    });

                    
                    // Mark that the first load has completed
                    $("#work-experience-grid-pjax").data("first-load", false);
                });
            });
        ');
    ?>

    <?php Pjax::end(); ?>
</div>
<?php
$this->registerJs("
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').fadeOut('slow');
        }, 3000);
    });
");
?>

<?php
        $script = '
        function deleteWorkExperienceFile(id){

            var con = confirm("Are you sure you want to delete this file?");
            if(con == true)
            {
                $.ajax({
                    url: "'.Url::to(['/file/file/delete']).'?id=" + id ,
                    type: "POST",
                    success: function (data) {
                        console.log(data);
                        viewWorkExperience("'.$model->emp_id.'");
                    },
                    error: function (err) {
                        console.log(err);
                    }
                }); 
            }

            return false;
        }
        ';

        $this->registerJs($script, View::POS_END);
    ?>
<style>
.isChecked {
  background-color: #F5F5F5 !important;
}
.bold-style {
    font-weight: bolder !important;
}
tr{
  background-color: white;
}
/* click-through element */
.check-education-item {
  pointer-events: none !important;
}

#work-experience-table > thead > tr{
    background-color: #F4F4F5; 
    color: black; 
    font-weight: bolder; 
}

#work-experience-table > thead > tr > td,
#work-experience-table > thead > tr > th
{
    border: 2px solid white;
}
</style>
