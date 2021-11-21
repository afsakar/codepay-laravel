<?php


return [

    [
        'title' => 'Dashboard',
        'gate' => 'dashboard',
        'description' => '',
        'permissions' => [
            'show' => 'Show',
            'read' => 'Read',
        ],
    ],
    [
        'title' => 'User Management',
        'gate' => 'user-management',
        'description' => 'User Management',
        'permissions' => [
            'show' => 'Show'
        ],
        'submenus' => [
            [
                'title' => 'Users',
                'gate' => 'users',
                'description' => '',
                'permissions' => [
                    'show' => 'Show',
                    'read' => 'Read',
                    'create' => 'Create',
                    'edit' => 'Edit',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Roles and Permissions',
                'gate' => 'roles',
                'description' => '',
                'permissions' => [
                    'show' => 'Show',
                    'read' => 'Read',
                    'create' => 'Create',
                    'edit' => 'Edit',
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
            'show' => 'Show',
        ],
    ],
    [
        'title' => 'Accounts',
        'gate' => 'accounts',
        'description' => '',
        'permissions' => [
            'show' => 'Show'
        ],
        'submenus' => [
            [
                'title' => 'Account List',
                'gate' => 'accounts',
                'description' => '',
                'permissions' => [
                    'show' => 'Show',
                    'read' => 'Read',
                    'create' => 'Create',
                    'edit' => 'Edit',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Account Types',
                'gate' => 'account_types',
                'description' => '',
                'permissions' => [
                    'show' => 'Show',
                    'read' => 'Read',
                    'create' => 'Create',
                    'edit' => 'Edit',
                    'delete' => 'Delete'
                ],
            ]
        ]
    ],

];
