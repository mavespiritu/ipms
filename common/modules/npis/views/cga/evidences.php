<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\web\View;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\bootstrap\ButtonDropdown;

?>
<?php 
    $cloneDataProvider = clone $dataProvider;
    $cloneDataProvider->pagination = false;
    if($cloneDataProvider->models){
        foreach($cloneDataProvider->models as $idx => $data){

            Modal::begin([
                'id' => 'update-evidence-modal-'.$data->id,
                'size' => 'modal-lg',
                'header' => '<div id="update-evidence-modal-'.$data->id.'-header"><h4>Edit Evidence</h4></div>',
                'options' => ['tabindex' => false],
            ]);
            echo '<div id="update-evidence-modal-'.$data->id.'-content"></div>';
            Modal::end();
        }    
    } 
?>
<div class="evidences-information">
    <h4>Evidences
    <span class="pull-right">
        <?= ButtonDropdown::widget([
        'label' => 'Add Evidence',
        'options' => ['class' => 'btn btn-success btn-sm'],
        'dropdown' => [
            'items' => [
                ['label' => 'Training', 'url' => '#', 'options' => ['id' => 'create-training-button', 'data-url' => Url::to(['/npis/cga/create-training', 'id' => $indicator->id, 'reference' => 'Training', 'emp_id' => $model->emp_id])]],
                ['label' => 'Award', 'url' => '#', 'options' => ['id' => 'create-award-button', 'data-url' => Url::to(['/npis/cga/select-award', 'id' => $indicator->id, 'reference' => 'Award', 'emp_id' => $model->emp_id])]],
                ['label' => 'Performance', 'url' => '#', 'options' => ['id' => 'create-performance-button', 'data-url' => Url::to(['/npis/cga/select-performance', 'id' => $indicator->id, 'reference' => 'Performance', 'emp_id' => $model->emp_id])]],
                ['label' => 'Others', 'url' => '#', 'options' => ['id' => 'create-others-button', 'data-url' => Url::to(['/npis/cga/create-others', 'id' => $indicator->id, 'reference' => 'Others', 'emp_id' => $model->emp_id])]],
            ],
        ],
    ]); ?>
    </span>
    </h4>
    <br>
    <div class="clearfix"></div>
    <?php $form = ActiveForm::begin([
        'options' => ['id' => 'evidence-check-form'],
    ]); ?>

    <?php  Pjax::begin([
        'id' => 'evidence-grid-pjax', 
        'enablePushState' => false, 
        'enableReplaceState' => false,
    ]);  ?>

    <div style="min-height: calc(100vh - 395px); max-height: 65vh; overflow-y: auto; padding: 10px;">

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

    <?php 
        $employee = clone $model;
    ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => function($model) use ($employee){
        $attachments = [];
        if($model->reference == 'Training'){
            if($model->evidenceTraining){
                if($model->evidenceTraining->training){
                    $attachments[] = $model->evidenceTraining->training->filePath;
                }
            }
        }else if($model->reference == 'Award'){
            if($model->evidenceAward){
                if($model->evidenceAward->award){
                    $attachments[] = $model->evidenceAward->award->filePath;
                }
            }
        }else if($model->reference == 'Performance'){
            if($model->evidencePerformance){
                if($model->evidencePerformance->performance){
                    $performance = $model->evidencePerformance->performance;
                    if($performance->files){
                        foreach($performance->files as $file){
                            $attachments[] = Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]);
                        }
                    }
                }
            }
        }else if($model->reference == 'Others'){
            if($model->files){
                foreach($model->files as $file){
                    $attachments[] = Html::a($file->name.'.'.$file->type, ['/file/file/download', 'id' => $file->id], ['download' => true, 'data-pjax' => 0]);
                }
            }
        }

        $date = '';

        if($model->reference == 'Training' || $model->reference == 'Others'){
            $date = $model->start_date != $model->end_date ? 
                date("Y", strtotime($model->start_date)) == date("Y", strtotime($model->end_date)) ?
                    date("F", strtotime($model->start_date)) == date("F", strtotime($model->end_date)) ?
                        date("F j", strtotime($model->start_date)).'-'.date("j, Y", strtotime($model->end_date)) : 
                        date("F j", strtotime($model->start_date)).'-'.date("F j, Y", strtotime($model->end_date)) : 
                date("F j, Y", strtotime($model->start_date)).'<br>-</br>'.date("F j, Y", strtotime($model->end_date)) : 
            date("F j, Y", strtotime($model->start_date));
        }else if($model->reference == 'Award' || $model->reference == 'Performance'){
            $date = date("Y", strtotime($model->start_date));
        }

        return '
            <br>
            <div class="box box-solid">
                <div class="box-body" style="min-height: auto !important; height: auto !important; padding: 10px 20px;">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div class="product-img" style="position: relative;">
                                <div class="'.$model->reference.'-logo-container">
                                    <div class="evidence-logo">'.substr($model->reference, 0, 1).'</div>
                                </div>
                            </div>
                            <div class="product-info">
                                <span class="pull-right text-right">
                                    '.$date.'<br>
                                    <br>
                                    <div class="text-right">
                                        <span class="edit-icon">'.Html::a('<i class="fa fa-pencil"></i>', '#', [
                                            'class' => 'update-evidence-button btn btn-xs btn-info',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#update-evidence-modal-'.$model->id,
                                            'data-url' => Url::to(['/npis/cga/update-'.strtolower($model->reference), 'id' => $model->id, 'emp_id' => $employee->emp_id]),
                                        ]).'</span>
                                        <span class="delete-icon">'.Html::a('<i class="fa fa-trash"></i>', 'javascript:void(0)', [
                                            'onClick' => 'deleteEvidence('.$model->id.', "'.$employee->emp_id.'")',
                                            'class' => 'btn btn-danger btn-xs',
                                        ]).'</span>
                                    </div>
                                </span>
                                <div style="width: 80%; text-align: justify; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;"><b>'.$model->title.'</b></div>
                                <span class="product-description" style="width: 80%; word-wrap: break-word; overflow-wrap: break-word; white-space: normal;">
                                '.$model->description.'
                                '.(!empty($attachments) ? '<small><i class="fa fa-file-text-o"></i> Attachments: <br></small>' : '').'
                                '.(!empty($attachments) ? implode("<br>", $attachments) : '').'
                                </span>
                                
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        ';
        },
        'layout' => "<div class='text-info'>{summary}</div>\n{items}\n{pager}",
        'pager' => [
                'firstPageLabel' => 'First',
                'lastPageLabel'  => 'Last',
                'prevPageLabel' => '<i class="fa fa-backward"></i>',
                'nextPageLabel' => '<i class="fa fa-forward"></i>',
            ],
    ]); ?>
    </div>

    <?php
        $this->registerJs('
            $(document).ready(function(){
                function initModal(modalId, contentUrl) {
                    $(modalId).modal("show").find(modalId + "-content").load(contentUrl);
                }

                $(".update-evidence-button").click(function(e){
                e.preventDefault();

                    var modalId = $(this).data("target");
                    var contentUrl = $(this).data("url");
                    initModal(modalId, contentUrl); // Initialize modal and content
                    return false;
                });

                $(document).on("pjax:success", function() {

                    if (!$("#evidence-grid-pjax").data("first-load")) {
                        return;
                    }
                    $(".update-evidence-button").each(function() {
                        var modalId = $(this).data("target");
                        var contentUrl = $(this).data("url");
                        initModal(modalId, contentUrl); // Reinitialize modal and content
                        return false;
                    });
                    // Mark that the first load has completed
                    $("#evidence-grid-pjax").data("first-load", false);
                });
            });
        ');
    ?>

    <?php Pjax::end(); ?>

    <?php ActiveForm::end(); ?>
</div>
<?php
    Modal::begin([
        'id' => 'create-training-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-training-modal-header"><h4>Add Evidence</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-training-modal-content"></div>';
    Modal::end();
?>
<?php
    Modal::begin([
        'id' => 'create-award-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-award-modal-header"><h4>Add Evidence</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-award-modal-content"></div>';
    Modal::end();
?>
<?php
    Modal::begin([
        'id' => 'create-performance-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-performance-modal-header"><h4>Add Evidence</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-performance-modal-content"></div>';
    Modal::end();
?>
<?php
    Modal::begin([
        'id' => 'create-others-modal',
        'size' => "modal-lg",
        'header' => '<div id="create-others-modal-header"><h4>Add Evidence</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="create-others-modal-content"></div>';
    Modal::end();
?>

<?php
    $script = '
        $(document).ready(function(){
            $("#create-training-button").click(function(){
                $("#create-training-modal").modal("show").find("#create-training-modal-content").load($(this).data("url"));
            });

            $("#create-award-button").click(function(){
                $("#create-award-modal").modal("show").find("#create-award-modal-content").load($(this).data("url"));
            });

            $("#create-performance-button").click(function(){
                $("#create-performance-modal").modal("show").find("#create-performance-modal-content").load($(this).data("url"));
            });

            $("#create-others-button").click(function(){
                $("#create-others-modal").modal("show").find("#create-others-modal-content").load($(this).data("url"));
            });

            $(".check-evidence-item").removeAttr("checked");
        });     
    ';

    $this->registerJs($script, View::POS_END);
?>

<?php
    $script = '
    function deleteEvidence(id){
        var con = confirm("Are you sure you want to delete this item?");

        if(con){
            $.ajax({
                url: "'.Url::to(['/npis/cga/delete-evidence']).'?id=" + id,
                type: "POST",
                data: {id: id},
                success: function (data) {
                    viewEvidences('.$indicator->id.', "'.$model->emp_id.'");
                    $("#evidence-badge-'.$indicator->id.'").html(parseInt($("#evidence-badge-'.$indicator->id.'").html()) - 1);
                },
                error: function (err) {
                    console.log(err);
                }
            });
        } 
    }
    ';

    $this->registerJs($script, View::POS_END);
?>

<?php
$this->registerJs("
    $(document).ready(function(){
        setTimeout(function(){
            $('.alert').fadeOut('slow');
        }, 3000);
    });
");
?>

<style>
.Training-logo-container {
  width: 40px; /* Adjust as needed */
  height: 40px; /* Adjust as needed */
  border-radius: 50%; /* Makes the container circular */
  background-color: #D56AA0; /* Background color of the container */
  display: flex;
  justify-content: center;
  align-items: center;
}

.Award-logo-container {
  width: 40px; /* Adjust as needed */
  height: 40px; /* Adjust as needed */
  border-radius: 50%; /* Makes the container circular */
  background-color: #861657; /* Background color of the container */
  display: flex;
  justify-content: center;
  align-items: center;
}

.Performance-logo-container {
  width: 40px; /* Adjust as needed */
  height: 40px; /* Adjust as needed */
  border-radius: 50%; /* Makes the container circular */
  background-color: #BBDBB4; /* Background color of the container */
  display: flex;
  justify-content: center;
  align-items: center;
}

.Others-logo-container {
  width: 40px; /* Adjust as needed */
  height: 40px; /* Adjust as needed */
  border-radius: 50%; /* Makes the container circular */
  background-color: #A64253; /* Background color of the container */
  display: flex;
  justify-content: center;
  align-items: center;
}

.evidence-logo {
  font-size: 20px; /* Adjust as needed */
  color: white; /* Color of the character */
  font-weight: bold; /* Adjust as needed */
}
</style>