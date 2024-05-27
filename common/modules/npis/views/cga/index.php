<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Cga */

$this->title = 'Staff CGA';
$this->params['breadcrumbs'][] = 'CGA';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cga-index">
    <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">Staff CGA</h3></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12">
                        <?= Yii::$app->user->can('DivisionChief') || Yii::$app->user->can('HR') ? $this->render('_search', ['model' => $model]) : '' ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                        <div id="staff-cga-profile"></div>
                    </div>
                </div>
            </div>
    </div>
</div>
