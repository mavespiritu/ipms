<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\modules\rpmes\models\RdpSubChapterOutcome */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Rdp Sub Chapter Outcomes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rdp-sub-chapter-outcome-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'rdp_chapter_id',
            'rdp_chapter_outcome_id',
            'level',
            'title:ntext',
            'description:ntext',
        ],
    ]) ?>

</div>
