<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stock Feature
    |--------------------------------------------------------------------------
    */

    'broker' => [
        'api' => 'users',
        'web' => 'users',
    ],

    'redirect_to' => '/backend',
    'register_enabled' => false,
    'forgot_enabled' => true,
    'routes_disabled' => false,
    'migration_autoload' => true,
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

    'cache_keys' => 'xMp9WzJAsGDzZLFS',

    //Invisible Recaptcha Google V2
    'recaptcha' => false,
    'recaptcha_secret' => '',
    'recaptcha_client' => '',

    'fields' => [
        'name' => 'text',
        'email' => 'email',
        'password' => 'password',
    ],

    'validator' => [
        'store' => [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ],
        'update' => [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'nullable|string|min:6|confirmed',
        ],
    ],

    'view' => [
        'email' => 'bendt-auth::passwords.email',
        'login' => 'bendt-auth::login',
        'register' => 'bendt-auth::register',
        'reset' => 'bendt-auth::passwords.reset',
    ],

    //Backward compatibility -- Not used since 1 April 2021
    'login_view' => 'view.email',
    'register_view' => 'view.login',
    'email_view' => 'view.register',
    'reset_view' => 'view.reset',
];
