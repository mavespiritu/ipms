<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>

<b>Dear <?= ($model->gender == 'Male' ? 'Mr.' : 'Ms.').' '.$model->fname.' '.$model->mname.' '.$model->lname ?>,</b>

<p>Please be informed that your entries in <?= $title ?> have been approved by the HR Unit. You can login to IPMS to see updates.</p>

<p style="text-align: center;"><a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/npis/pds/view']) ?>" style="box-sizing:border-box; border-radius:4px; color:#fff; display:inline-block; overflow:hidden; text-decoration:none; background-color:#2d3748; border-bottom:8px solid #2d3748; border-left:18px solid #2d3748; border-right:18px solid #2d3748; border-top:8px solid #2d3748">See Updates</a></p>

<p>Regards,</p>
<p>NRO1 IPMS</p>
<br>
<hr>
<small>
<i>This email is generated automatically by our information system. Please note that this is not a monitored inbox, and replies to this email will not be attended to. If you have any inquiries or concerns, kindly contact our support team through the designated channels. Thank you.</i></small>
