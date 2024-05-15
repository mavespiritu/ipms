<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\modules\npis\models\Ipcr */
?>
<div>
    <h4>Personal Information
        <br>
        <span style="font-weight: normal; font-size: 14px;">PDS Page 1 of 4</span>
    </h4>
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
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-condensed table-responsive table-bordered inverted-table">
                    <tr>
                        <th style="width: 20%;">SURNAME </th>
                        <td style="width: 30%;" colspan=3><?= $model->lname ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">FIRSTNAME </th>
                        <td style="width: 30%;" colspan=3><?= $model->fname ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">MIDDLENAME </th>
                        <td style="width: 30%;" colspan=3><?= $model->mname ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">DATE OF BIRTH<br>(mm/dd/yyyy) </th>
                        <td style="width: 30%;"><?= date("m/d/Y", strtotime($model->birth_date)) ?></td>
                        <th style="width: 20%;" rowspan=3>CITIZENSHIP </th>
                        <td style="width: 30%;" rowspan=3><?= $model->citizenship ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">PLACE OF BIRTH</th>
                        <td style="width: 30%;"><?= $model->birth_place ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">SEX</th>
                        <td style="width: 30%;"><?= $model->gender ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">CIVIL STATUS</th>
                        <td style="width: 30%;"><?= $model->civil_status ?></td>
                        <th style="width: 20%;">RESIDENTIAL ADDRESS</th>
                        <td style="width: 30%;"><?= $model->residential_address ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">HEIGHT (m)</th>
                        <td style="width: 30%;"><?= $model->height ?></td>
                        <th style="width: 20%;">ZIP CODE</th>
                        <td style="width: 30%;"><?= $model->residential_zip_code ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">WEIGHT (kg)</th>
                        <td style="width: 30%;"><?= $model->weight ?></td>
                        <th style="width: 20%;">PERMANENT ADDRESS</th>
                        <td style="width: 30%;"><?= $model->permanent_address ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">BLOOD TYPE</th>
                        <td style="width: 30%;"><?= $model->blood_type ?></td>
                        <th style="width: 20%;">ZIP CODE</th>
                        <td style="width: 30%;"><?= $model->permanent_zip_code ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">GSIS ID NO.</th>
                        <td style="width: 30%;"><?= $model->GSIS ?></td>
                        <th style="width: 20%;">TELEPHONE NO.</th>
                        <td style="width: 30%;"><?= $model->permanent_tel_no ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">SSS NO.</th>
                        <td style="width: 30%;"><?= $model->SSS ?></td>
                        <th style="width: 20%;">MOBILE NO.</th>
                        <td style="width: 30%;"><?= $model->cell_no ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">TIN NO.</th>
                        <td style="width: 30%;"><?= $model->TIN ?></td>
                        <th style="width: 20%;">E-MAIL ADDRESS (if any)</th>
                        <td style="width: 30%;"><?= $model->e_mail_add ?></td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">AGENCY EMPLOYEE NO.</th>
                        <td style="width: 30%;" colspan=3><?= $model->emp_id ?></td>
                    </tr>
                </table>
            </div>
            <div class="pull-right">
                <?= Html::button('Edit Information', ['value' => Url::to(['/npis/pds/update-personal-information', 'id' => $model->emp_id]), 'class' => 'btn btn-success', 'id' => 'personal-information-button']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php
    Modal::begin([
        'id' => 'personal-information-modal',
        'size' => "modal-lg",
        'header' => '<div id="personal-information-modal-header"><h4>Edit Information</h4></div>',
        'options' => ['tabindex' => false],
    ]);
    echo '<div id="personal-information-modal-content"></div>';
    Modal::end();
?>
<?php
    $script = '
        $(document).ready(function(){
            $("#personal-information-button").click(function(){
                $("#personal-information-modal").modal("show").find("#personal-information-modal-content").load($(this).attr("value"));
              });
        });     
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