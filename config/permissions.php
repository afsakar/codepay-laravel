<?php


return [

    [
        'title' => 'Dashboard',
        'gate' => 'dashboard',
        'description' => '',
        'permissions' => [
            'read' => 'Read',
        ],
    ],
    [
        'title' => 'User Management',
        'gate' => 'user-management',
        'description' => 'User Management',
        'permissions' => [
            'read' => 'Read',
        ],
        'submenus' => [
            [
                'title' => 'Users',
                'gate' => 'users',
                'description' => '',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Roles and Permissions',
                'gate' => 'roles',
                'description' => '',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ],
        ]
    ],
    [
        'title' => 'Translations',
        'gate' => 'translations',
        'description' => '',
        'permissions' => [
            'read' => 'Read',
        ],
    ],
    [
        'title' => 'Companies',
        'gate' => 'companies',
        'description' => 'Company Management',
        'permissions' => [
            'create' => 'Create',
            'read' => 'Read',
            'update' => 'Update',
            'delete' => 'Delete'
        ],
    ],
    [
        'title' => 'Accounts',
        'gate' => 'accounts',
        'description' => '',
        'permissions' => [
            'read' => 'Read',
        ],
        'submenus' => [
            [
                'title' => 'Account List',
                'gate' => 'accounts',
                'description' => '',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Account Types',
                'gate' => 'account_types',
                'description' => '',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ]
        ]
    ],

];
