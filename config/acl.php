<?php

return [
    'roles' => [
        'sa' => [
            'edit user',
            'delete user',
            'create user',
            'list user',
            'list users',
            'edit own user',
            'list own user',
        ],
        'user' => [
            'edit own user',
            'list own user',

            'edit own product',
            'list own product',
        ],
        'patient' => [
            'edit own user',
            'list own user',

            'edit own product',
            'list own product',
        ],
        'professional' => [
            'edit own user',
            'list own user',

            'edit own product',
            'list own product',
        ]
    ],
    'env' => env('APP_ENV', 'production'),
];
