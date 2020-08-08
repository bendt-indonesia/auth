<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stock Feature
    |--------------------------------------------------------------------------
    */

    'redirect_to' => '/backend',
    'register_enabled' => false,
    'forgot_enabled' => true,
    'login_view' => 'bendt-auth::login',
    'register_view' => 'bendt-auth::register',
    'email_view' => 'bendt-auth::passwords.email',
    'reset_view' => 'bendt-auth::passwords.reset',
    'routes_disabled' => false,
    'passport' => false,
    'passport_expire_in_minute' => 0,

    'response' => [
        'resetLink' => [
            'base_url' => '', //if exists then using this (end with / ), otherwise using url()
            'response_type' => 'json', //json or redirect
            'success_msg' => 'We\'ll sent a e-mail instruction to registered e-mail address.',
            'error_msg' => 'We\'ll sent a e-mail instruction to registered e-mail address.',
        ],
        'reset' => [
            'response_type' => 'json',
            'success_msg' => 'Your password has been successfully saved!',
            'error_msg' => 'This password reset token is invalid.',
        ]
    ],

    'cache_keys' => 'xMp9WzJAsGDzZLFS'
];
