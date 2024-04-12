<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\dtr\models\Dtr */

$this->title = $model->emp_id;
$this->params['breadcrumbs'][] = ['label' => 'Dtrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dtr-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'emp_id' => $model->emp_id, 'date' => $model->date, 'time' => $model->time, 'time_in' => $model->time_in], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'emp_id' => $model->emp_id, 'date' => $model->date, 'time' => $model->time, 'time_in' => $model->time_in], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'emp_id',
            'date',
            'time',
            'time_in',
            'time_out',
            'year',
            'month',
        ],
    ]) ?>

</div>
