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
        'title' => 'Settings',
        'gate' => 'settings',
        'description' => 'Application Settings',
        'permissions' => [
            'read' => 'Read',
        ],
        'submenus' => [
            [
                'title' => 'Translations',
                'gate' => 'translations',
                'description' => '',
                'permissions' => [
                    'read' => 'Read',
                ],
            ],
            [
                'title' => 'Currencies',
                'gate' => 'currencies',
                'description' => '',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Categories',
                'gate' => 'categories',
                'description' => 'Revenue & Payment Categories',
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
        'title' => 'Sales',
        'gate' => 'sales',
        'description' => 'Sales Management',
        'permissions' => [
            'read' => 'Read',
        ],
        'submenus' => [
            [
                'title' => 'Customers',
                'gate' => 'customers',
                'description' => 'Customer Management',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Revenues',
                'gate' => 'revenues',
                'description' => 'Revenue Management',
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
        'title' => 'Purchases',
        'gate' => 'purchases',
        'description' => 'Purchase Management',
        'permissions' => [
            'read' => 'Read',
        ],
        'submenus' => [
            [
                'title' => 'Suppliers',
                'gate' => 'suppliers',
                'description' => 'Supplier Management',
                'permissions' => [
                    'create' => 'Create',
                    'read' => 'Read',
                    'update' => 'Update',
                    'delete' => 'Delete'
                ],
            ],
            [
                'title' => 'Expenses',
                'gate' => 'expenses',
                'description' => 'Expense Management',
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
        'title' => 'Banks',
        'gate' => 'banks',
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
