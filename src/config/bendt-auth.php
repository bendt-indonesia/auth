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
    'routes_disabled' => false
];
