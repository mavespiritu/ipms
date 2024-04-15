<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">
    <?php if(!Yii::$app->user->isGuest){ ?>
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="background-color: #00766A; color: white;">
            <span class="sr-only">Toggle navigation</span>
        </a>
    <?php } ?>
    <?= !Yii::$app->user->isGuest ? Html::a('<span class="logo-mini">IPMSv2</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) : '' ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <?php if(Yii::$app->user->isGuest){ ?>
            <div class="navbar-header">
                <a href="#" class="navbar-brand">eRPMES</a>
            </div>
        <?php } ?>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <?php if(!Yii::$app->user->isGuest){ ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/site/profile-picture" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->userinfo->fullName ?> &nbsp;<i class="fa fa-angle-down"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/site/profile-picture" class="img-circle"
                                 alt="User Image"/>

                            <p style="font-size: 12px;">
                                <?= Yii::$app->user->identity->userinfo->fullName ?>
                            </p>
                        </li>   
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a('Change Password', ['/user/settings/account'], ['class' => 'btn btn-default btn-flat']) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/sso/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            <?php } ?>
            </ul>
        </div>
    </nav>
</header>
