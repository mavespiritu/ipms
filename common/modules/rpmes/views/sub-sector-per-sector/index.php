<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\modules\rpmes\models\SubSectorPerSectorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sub Sector Per Sectors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-sector-per-sector-index">

    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Add New', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'options' => [
            'class' => 'table-responsive',
        ],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sectorTitle',
            'subSectorTitle',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}{delete}'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
