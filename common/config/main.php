<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@file' => dirname(__DIR__),
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'modules' => [
        'dashboard' => [
            'class' => 'common\modules\dashboard\Dashboard',
        ],
        'npis' => [
            'class' => 'common\modules\npis\Npis',
        ],
        'dtr' => [
            'class' => 'common\modules\dtr\Dtr',
        ],
        'file' => [
            'class' => 'file\FileModule',
            'webDir' => 'files',
            'tempPath' => '@frontend/web/temp',
            'storePath' => '@frontend/web/store',
            'rules' => [
                'maxFiles' => 10,
                'maxSize' => 1024 * 1024 * 10, // 2 MB
                'mimeTypes' => [
                    'application/pdf',
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-excel',
                ],
            ],
        ],
        'user' => [
            'class' => 'markavespiritu\user\Module',
            'admins' => ['markavespiritu'],
            'enableRegistration' => false,
            'enableConfirmation' => false,
            'enablePasswordRecovery' => false,
            'mailer' => [
                    'sender'                => 'nro1.mailer@neda.gov.ph',
                    'welcomeSubject'        => 'Welcome to the IPMS',
                    'confirmationSubject'   => 'Confirm your IPMS account',
                    'reconfirmationSubject' => 'Reconfirm your IPMS account',
                    'recoverySubject'       => 'Recover your IPMS account',
            ],
            'controllerMap' => [
                'admin' => [
                    'class' => 'markavespiritu\user\controllers\AdminController',
                    'as access' => [
                        'class' => 'yii\filters\AccessControl',
                        'rules' => [
                            [
                                'allow' => true,
                                'roles' => ['SuperAdministrator','Administrator'],
                            ],
                            [
                                'actions' => ['switch'],
                                'allow' => true,
                                'roles' => ['SuperAdministrator'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'utility' => [
            'class' => 'c006\utility\migration\Module',
        ],
        'rbac' => [
            'class' => 'markavespiritu\rbac\RbacWebModule',
        ],
        'sso' => [
            'class' => 'common\modules\sso\Sso',
        ],
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'accessRoles' => ['SuperAdministrator'],
        ],
    ]
];
