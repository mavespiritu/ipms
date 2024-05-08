<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'My CGA';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
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
<div id="alert" class="alert" role="alert" style="display: none;"></div>
<div class="ipcr-view">
    <div class="box box-solid">
        <div class="box-header with-border"><h3 class="box-title">Competency Gap Analysis Form</h3></div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-3 col-lg-2 col-xs-12">
                    <div class="user-block">
                        <span class="description" style="margin-left: 0 !important;">
                            Item No.: <?= $model->item_no ?><br>
                            Position: <?= $model->positionItem->position_id ?><br>
                            Division: <?= $model->positionItem->division_id ?><br>
                            SG and Step: <?= $model->positionItem->grade.'-'.$model->positionItem->step ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-9 col-lg-10 col-xs-12">
                    <h5>Required Competencies</h5>
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