<?php
/**
 * Created by PhpStorm.
 * User: benwalandow
 * Date: 17/07/19
 * Time: 13.16
 */

Route::group([
    'namespace' => 'Bendt\Auth\Controllers\API',
    'middleware' => 'auth:api'
], function() {
    Route::get('/api/user', 'UserController@index');
    Route::post('/api/logout', 'AuthController@logout');
    Route::get('/api/logout', 'AuthController@logout');
    Route::get('/api/roles', 'RoleGroupController@roles');
    Route::post('/api/roles', 'RoleGroupController@saveRoles');
});

Route::group([
    'namespace' => 'Bendt\Auth\Controllers\API',
    'middleware' => \Bendt\Auth\Middleware\RecaptchaAPI::class,
], function() {
    Route::post('/api/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('passport.password.email');
    Route::post('/api/password/reset', 'ResetPasswordController@reset')->name('passport.password.reset');
});