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
});