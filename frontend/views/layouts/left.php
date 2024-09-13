<aside class="main-sidebar">

    <section class="sidebar">
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'MAIN MENU', 'options' => ['class' => 'header']],
                    ['label' => 'Home', 'icon' => 'home', 'url' => ['/dashboard']],
                    [
                        'label' => 'NPIS',
                        'icon' => 'folder',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('Staff'),
                        'items' => [
                            ['label' => 'My PDS', 'icon' => 'folder', 'url' => ['/npis/pds/view'], 'visible' => '@'],
                            ['label' => 'Staff PDS', 'icon' => 'folder', 'url' => ['/npis/pds'], 'visible' => Yii::$app->user->can('HR')],
                            ['label' => 'Staff Profile', 'icon' => 'folder', 'url' => ['/npis/staff-profile'], 'visible' => Yii::$app->user->can('HR')],
                            [
                                'label' => Yii::$app->user->can('HR') ? 'Staff 201' : 'My 201',
                                'icon' => 'folder',
                                'url' => '#',
                                'visible' => Yii::$app->user->can('Staff'),
                                'items' => [
                                    ['label' => 'IPCR', 'icon' => 'folder', 'url' => ['/npis/ipcr'], 'visible' => Yii::$app->user->can('Staff')],
                                    ['label' => 'Special Order', 'icon' => 'folder', 'url' => ['/npis/special-order'], 'visible' => Yii::$app->user->can('Staff')],
                                    ['label' => 'Disciplinary Action', 'icon' => 'folder', 'url' => ['/npis/disciplinary-action'], 'visible' => Yii::$app->user->can('Staff')],
                                    ['label' => 'Service Contract', 'icon' => 'folder', 'url' => ['/npis/service-contract'], 'visible' => Yii::$app->user->can('Staff')],
                                    ['label' => 'Medical Certificate', 'icon' => 'folder', 'url' => ['/npis/medical-certificate'], 'visible' => Yii::$app->user->can('Staff')],
                                    ['label' => 'NBI Clearance', 'icon' => 'folder', 'url' => ['/npis/nbi-clearance'], 'visible' => Yii::$app->user->can('Staff')],
                                    ['label' => 'Other Documents', 'icon' => 'folder', 'url' => ['/npis/other-document'], 'visible' => Yii::$app->user->can('Staff')],
                                ],
                                
                            ],
                        ],
                        
                    ],
                    [
                        'label' => 'DTR',
                        'icon' => 'folder',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('Staff'),
                        'items' => [
                            [
                                'label' => 'My DTR Information',
                                'icon' => 'folder',
                                'url' => '#',
                                'visible' => Yii::$app->user->can('Staff'),
                                'items' => [
                                    ['label' => 'FWA', 'icon' => 'folder', 'url' => ['/dtr/fwa'], 'visible' => Yii::$app->user->can('Staff')],
                                ],
                                
                            ],
                        ],
                        
                    ],
                    [
                        'label' => 'Competencies',
                        'icon' => 'folder',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('Staff'),
                        'items' => [
                            ['label' => 'Staff CGA', 'icon' => 'folder', 'url' => ['/npis/cga'], 'visible' => Yii::$app->user->can('DivisionChief') || Yii::$app->user->can('HR')],
                            ['label' => 'My CGA', 'icon' => 'folder', 'url' => ['/npis/cga/view'], 'visible' => Yii::$app->user->can('Staff')],
                            ['label' => 'Dictionary', 'icon' => 'folder', 'url' => ['/npis/competency'], 'visible' => Yii::$app->user->can('HR')],
                            ['label' => 'Indicators', 'icon' => 'folder', 'url' => ['/npis/indicator'], 'visible' => Yii::$app->user->can('HR')],
                            ['label' => 'LSPs', 'icon' => 'folder', 'url' => ['/npis/lsp'], 'visible' => Yii::$app->user->can('HR')],
                            ['label' => 'Trainings', 'icon' => 'folder', 'url' => ['/npis/training'], 'visible' => Yii::$app->user->can('Staff')],
                            ['label' => 'Positions', 'icon' => 'folder', 'url' => ['/npis/position'], 'visible' => Yii::$app->user->can('Staff')],
                            ['label' => 'Designations', 'icon' => 'folder', 'url' => ['/npis/designation'], 'visible' => Yii::$app->user->can('HR')],
                        ],
                        
                    ],
                    
                    ['label' => 'Administrator', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->can('SuperAdministrator'), Yii::$app->user->can('SuperAdministrator')],
                    ['label' => 'User Management', 'icon' => 'users', 'url' => ['/user/admin'], 'visible' => Yii::$app->user->can('SuperAdministrator')],
                ],
            ]
        ) ?>

    </section>

</aside>
