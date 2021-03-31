<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stock Feature
    |--------------------------------------------------------------------------
    */

    //Auth Broker
    'broker' => [
        'api' => 'users',
        'web' => 'users',
    ],

    //Auth Cache Keys
    'cache_keys' => 'xMp9WzJAsGDzZLFS',

    //Redirect after Login & Registration
    'redirect_to' => '/backend',

    //Function
    'register_enabled' => false,
    'forgot_enabled' => true,
    'routes_disabled' => false,

    //Migration for Bendt modules & Roles
    'migration_autoload' => true,
    'passport' => false,
    'passport_expire_in_minute' => 0,

    //Response
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

    //Invisible Recaptcha Google V2
    'recaptcha' => false,
    'recaptcha_secret' => '',
    'recaptcha_client' => '',

    'fields' => [
        'name' => 'text',
        'email' => 'email',
        'password' => 'password',
    ],

    //Validator for registration & update
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

    //View
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
