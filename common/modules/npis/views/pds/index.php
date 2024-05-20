<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */

$this->title = 'Staff PDS';
$this->params['breadcrumbs'][] = 'NPIS';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pds-view">
    <div class="box box-solid">
            <div class="box-header with-border"><h3 class="box-title">Staff PDS</h3></div>
            <div class="box-body">
                <?= Yii::$app->user->can('HR') ? $this->render('_search', ['model' => $model]) : '' ?>
                <div id="staff-profile"></div>
            </div>
    </div>
</div>
<style>
    table.inverted-table th{
        background-color: #F4F4F5;  
        font-weight: normal; 
        border: 1px solid #ECF0F5 !important;
        text-align: right;
    }
    table.inverted-table td{
        font-weight: bolder !important;
        border: 1px solid #ECF0F5 !important;
    }
</style>
