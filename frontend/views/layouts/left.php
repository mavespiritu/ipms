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
                            ['label' => 'Staff PDS', 'icon' => 'folder', 'url' => ['/npis/pds/'], 'visible' => Yii::$app->user->can('HR')],
                            /* [
                                'label' => 'Staff Profile',
                                'icon' => 'folder',
                                'url' => '#',
                                'visible' => Yii::$app->user->can('HR'),
                                'items' => [
                                    ['label' => 'Personal information', 'icon' => 'folder', 'url' => ['/npis/personal-information'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Family Background', 'icon' => 'folder', 'url' => ['/npis/family-background'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Educational Background', 'icon' => 'folder', 'url' => ['/npis/educational-background'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Civil Service Eligibility', 'icon' => 'folder', 'url' => ['/npis/civil-service-eligibility'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Work Experience', 'icon' => 'folder', 'url' => ['/npis/work-experience'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Voluntary Work', 'icon' => 'folder', 'url' => ['/npis/voluntary-work'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Learning & Development', 'icon' => 'folder', 'url' => ['/npis/training'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Other information', 'icon' => 'folder', 'url' => ['/npis/other-information'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'Questions', 'icon' => 'folder', 'url' => ['/npis/question'], 'visible' => Yii::$app->user->can('HR')],
                                    ['label' => 'References', 'icon' => 'folder', 'url' => ['/npis/references'], 'visible' => Yii::$app->user->can('HR')],
                                ],
                                
                            ], */
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
                        'label' => 'CGA',
                        'icon' => 'folder',
                        'url' => '#',
                        'visible' => Yii::$app->user->can('HR'),
                        'items' => [
                            //['label' => 'My CGA', 'icon' => 'folder', 'url' => ['npis/cga/view'], 'visible' => Yii::$app->user->can('Staff')],
                            //['label' => 'Staff CGA', 'icon' => 'folder', 'url' => ['npis/cga/'], 'visible' => Yii::$app->user->can('HR')],
                            //['label' => 'Setup', 'icon' => 'folder', 'url' => ['npis/cga/setup'], 'visible' => Yii::$app->user->can('HR')],
                            ['label' => 'LSP', 'icon' => 'folder', 'url' => ['/npis/lsp/'], 'visible' => Yii::$app->user->can('HR')],
                            ['label' => 'Trainings', 'icon' => 'folder', 'url' => ['/npis/training/'], 'visible' => Yii::$app->user->can('HR')],
                        ],
                        
                    ],
                    
                    ['label' => 'Administrator', 'options' => ['class' => 'header'], Yii::$app->user->can('SuperAdministrator')],
                    /* [
                        'label' => 'Presets',
                        'icon' => 'cog',
                        'url' => '#',
                        'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles)),
                        'items' => [
                            ['label' => 'Agencies', 'icon' => 'folder', 'url' => ['/rpmes/agency'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Agency Types', 'icon' => 'folder', 'url' => ['/rpmes/agency-type'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Categories', 'icon' => 'folder', 'url' => ['/rpmes/category'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Fund Sources', 'icon' => 'folder', 'url' => ['/rpmes/fund-source'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'KRA/Clusters', 'icon' => 'folder', 'url' => ['/rpmes/key-result-area'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Programs', 'icon' => 'folder', 'url' => ['/rpmes/program'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Sectors', 'icon' => 'folder', 'url' => ['/rpmes/sector'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Sub-Sectors', 'icon' => 'folder', 'url' => ['/rpmes/sub-sector'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Sub-Sectors By Sectors', 'icon' => 'folder', 'url' => ['/rpmes/sub-sector-per-sector'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            ['label' => 'Event Upload', 'icon' => 'folder', 'url' => ['/rpmes/event-image'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                            [
                                'label' => 'RDP-related',
                                'icon' => 'folder',
                                'url' => '#',
                                'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles)),
                                'items' => [
                                    ['label' => 'SDG Goals', 'icon' => 'folder', 'url' => ['/rpmes/sdg-goal'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                                    ['label' => 'Chapters', 'icon' => 'folder', 'url' => ['/rpmes/rdp-chapter'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                                    ['label' => 'Chapter Outcomes', 'icon' => 'folder', 'url' => ['/rpmes/rdp-chapter-outcome'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                                    ['label' => 'Sub-Chapter Outcomes', 'icon' => 'folder', 'url' => ['/rpmes/rdp-sub-chapter-outcome'], 'visible' => !Yii::$app->user->isGuest && (in_array('SuperAdministrator', $userRoles) || in_array('Administrator', $userRoles))],
                                ],
                                
                            ],
                        ],
                        
                    ], */
                    ['label' => 'User Management', 'icon' => 'users', 'url' => ['/user/admin'], 'visible' => Yii::$app->user->can('SuperAdministrator')],
                ],
            ]
        ) ?>

    </section>

</aside>
