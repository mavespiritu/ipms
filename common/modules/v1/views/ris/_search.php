<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;
use yii\web\View;
/* @var $model common\modules\v1\models\PpmpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ris-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>
    
    <?= $form->field($model, 'ris_no') ?>

    <div class="row">
        <?php if(Yii::$app->user->can('Administrator') || Yii::$app->user->can('Procurement') || Yii::$app->user->can('Accounting')){ ?>
            <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'office_id')->widget(Select2::classname(), [
                'data' => ['' => 'All Divisions'] + $offices,
                'options' => ['multiple' => false, 'class'=>'office-select'],
                'pluginOptions' => [
                    'allowClear' =>  false,
                ],
            ]);
            ?>
            </div>
        <?php } ?>
        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'type')->widget(Select2::classname(), [
                'data' => $types,
                'options' => ['placeholder' => 'Select Type','multiple' => false, 'class'=>'type-select'],
                'pluginOptions' => [
                    'allowClear' =>  true,
                ],
                ])->label('Type');
            ?>
        </div>
        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'fund_source_id')->widget(Select2::classname(), [
                'data' => $fundSources,
                'options' => ['placeholder' => 'Select Fund Source','multiple' => false, 'class'=>'fund-source-select'],
                'pluginOptions' => [
                    'allowClear' =>  true,
                ],
                ]);
            ?>
        </div>

        <div class="col-md-3 col-xs-12">
            <?= $form->field($model, 'date_required')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter date'],
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ],
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'purpose')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Clear', ['class' => 'btn btn-outline-secondary', 'onClick' => 'redirectPage()']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = '
    function redirectPage()
    {
        window.location.href = "'.Url::to(['/v1/ris/']).'";
    }
';
$this->registerJs($script, View::POS_END);
?>