<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\v1\models\Ris */

$this->title = $model->status ? $model->ris_no.' ['.$model->status->status.']' : $model->ris_no;
$this->params['breadcrumbs'][] = ['label' => 'RIS', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="ris-realign">
    <?= $this->render('_menu', [
        'model' => $model
    ]) ?>

    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header panel-title"><i class="fa fa-edit"></i> Realign Items</div>
                <div class="box-body">
                    <center>
                        <p>Amount to Re-align:</p>
                        <div class="flex-center" style="width: 50%;">
                            <div>Minimum:<h4><?= number_format($model->getRealignAmount(), 2) ?></h4></div>
                            <div style="width: 80%"><hr style="border-top: dotted 2px; width: 80%; " /></div>
                            <div>Maximum:<h4><?= number_format($model->getRealignAmount() + ($model->getItemsTotal('Supplemental') * 0.20), 2) ?></h4></div>
                        </div>
                    </center>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <?= $this->render('_realign-items', [
                                'model' => $model,
                                'appropriationItemModel' => $appropriationItemModel,
                                'activities' => $activities,
                                'subActivities' => $subActivities,
                            ]) ?>
                            <br>
                            <p class="panel-title"><i class="fa fa-list"></i> Available PPMP Items</p><br>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div id="ris-realign-item-list">
                                        <p class="text-center">No items selected.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="box box-primary">
                <div class="box-header panel-title"><i class="fa fa-list"></i> Realigned Items</div>
                <div class="box-body">
                    <div id="realigned-items"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
  $script = '
    function loadRealignedItems()
    {
        $.ajax({
            url: "'.Url::to(['/v1/ris/realigned']).'",
            data: {
                id: '.$model->id.'
            },
            beforeSend: function(){
                $("#realigned-items").html("<div class=\"text-center\" style=\"margin-top: 50px;\"><svg class=\"spinner\" width=\"30px\" height=\"30px\" viewBox=\"0 0 66 66\" xmlns=\"http://www.w3.org/2000/svg\"><circle class=\"path\" fill=\"none\" stroke-width=\"6\" stroke-linecap=\"round\" cx=\"33\" cy=\"33\" r=\"30\"></circle></svg></div>");
            },
            success: function (data) {
                console.log(this.data);
                $("#realigned-items").empty();
                $("#realigned-items").hide();
                $("#realigned-items").fadeIn("slow");
                $("#realigned-items").html(data);
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    $(document).ready(function() {
        loadRealignedItems();
    });
  ';
  $this->registerJs($script, View::POS_END);
?>