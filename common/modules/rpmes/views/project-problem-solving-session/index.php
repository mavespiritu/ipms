<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\View;
use yii\bootstrap\ButtonDropdown;
use yii\widgets\LinkPager;
use common\components\helpers\HtmlHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\rpmes\models\ProjectProblemSolvingSessionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RPMES Form 8: Problem-Solving Session/Facilitation Meeting Conducted';
$this->params['breadcrumbs'][] = $this->title;

$successMessage = \Yii::$app->getSession()->getFlash('success');
?>
<div class="project-problem-solving-session-index">
    <div class="flash-success" style="display: none;">
        <?= $successMessage ?>
    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">List of Problem-Solving Session/Facilitation Meeting Conducted</h3>
        </div>
        <div class="box-body">
            <?= $this->render('_search', ['model' => $searchModel]) ?>
            <?= GridView::widget([
                'options' => [
                    'class' => 'table-responsive',
                ],
                'tableOptions' => [
                    'class' => 'table table-bordered table-striped table-hover',
                ],
                'dataProvider' => $dataProvider,
                'columns' => Yii::$app->user->can('Administrator') ? [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => [
                            'style' => 'background-color: #002060; color: white; font-weight: normal;'
                        ],
                    ],
                    [
                        'attribute' => 'year',
                        'header' => 'Year',
                        'headerOptions' => [
                            'style' => 'width: 5%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'quarter',
                        'header' => 'Quarter',
                        'headerOptions' => [
                            'style' => 'width: 5%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'header' => '
                                    (a) Project No. <br>
                                    (b) Program/Project Title <br>
                                    (c) Province <br>
                                    (d) City/Municipality <br>
                                    (e) Barangay
                                    ',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ],
                        'format' => 'raw',
                        'value' => function($model){
                            return 
                                '(a) '.$model->project->project_no.'<br>'.
                                '(b) '.$model->project->title.'<br>'.
                                '(c) '.$model->project->provinceTitle.'<br>'.
                                '(d) '.$model->project->citymunTitle.'<br>'.
                                '(e) '.$model->project->barangayTitle
                            ;
                        }
                    ],
                    [
                        'attribute' => 'issue_details',
                        'header' => 'Issue Details',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'issue_typology',
                        'header' => 'Issue Typology',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'project.agency.code',
                        'header' => 'Implementing Agency',
                        'headerOptions' => [
                            'style' => 'width: 5%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'pss_date',
                        'header' => 'Date of Meeting',
                        'headerOptions' => [
                            'style' => 'width: 10%; background-color: #002060; color: white; font-weight: normal;'
                        ],
                        'format' => 'raw',
                        'value' => function ($model){
                            return date("F j, Y", strtotime($model->pss_date)); 
                        }
                    ],
                    [
                        'attribute' => 'agencies',
                        'header' => 'Concerned Agencies',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'agreement_reached',
                        'header' => 'Agreements Reached',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn', 
                        'headerOptions' => [
                            'style' => 'background-color: #002060; color: white; font-weight: normal;'
                        ],
                        'template' => '<center>{update} {delete}</center>',
                        'buttons' => [
                            'update' => function($url, $model, $key){
                                $modalID = $model->id;
                                return Html::a('Update', ['update', 'id' => $model->id], [
                                    'class' => 'btn btn-warning btn-block btn-xs'
                                ]);
                            },
                            'delete' => function($url, $model, $key){
                                return Html::a('Delete', ['delete', 'id' => $model->id], [
                                                    'class' => 'btn btn-danger btn-block btn-xs',
                                                    'data' => [
                                                        'confirm' => 'Are you sure want to delete this item?',
                                                        'method' => 'post',
                                                    ],
                                                ]);
                            },
                        ],
                    ],
                ] : [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => [
                            'style' => 'background-color: #002060; color: white; font-weight: normal;'
                        ],
                    ],
                    [
                        'attribute' => 'year',
                        'header' => 'Year',
                        'headerOptions' => [
                            'style' => 'width: 5%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'quarter',
                        'header' => 'Quarter',
                        'headerOptions' => [
                            'style' => 'width: 5%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'header' => '
                                    (a) Project No. <br>
                                    (b) Program/Project Title <br>
                                    (c) Province <br>
                                    (d) City/Municipality <br>
                                    (e) Barangay
                                    ',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ],
                        'format' => 'raw',
                        'value' => function($model){
                            return 
                                '(a) '.$model->project->project_no.'<br>'.
                                '(b) '.$model->project->title.'<br>'.
                                '(c) '.$model->project->provinceTitle.'<br>'.
                                '(d) '.$model->project->citymunTitle.'<br>'.
                                '(e) '.$model->project->barangayTitle
                            ;
                        }
                    ],
                    [
                        'attribute' => 'issue_details',
                        'header' => 'Issue Details',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'issue_typology',
                        'header' => 'Issue Typology',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'project.agency.code',
                        'header' => 'Implementing Agency',
                        'headerOptions' => [
                            'style' => 'width: 5%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'pss_date',
                        'header' => 'Date of Meeting',
                        'headerOptions' => [
                            'style' => 'width: 10%; background-color: #002060; color: white; font-weight: normal;'
                        ],
                        'format' => 'raw',
                        'value' => function ($model){
                            return date("F j, Y", strtotime($model->pss_date)); 
                        }
                    ],
                    [
                        'attribute' => 'agencies',
                        'header' => 'Concerned Agencies',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                    [
                        'attribute' => 'agreement_reached',
                        'header' => 'Agreements Reached',
                        'headerOptions' => [
                            'style' => 'width: 15%; background-color: #002060; color: white; font-weight: normal;'
                        ]
                    ],
                ],
            ]); ?>
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
            }, 5000);
        });
    ");
}
?>