<?php

/**
 * Throw Error String Generator
 *
 * @param string $code
 * @param string $message
 * @param int $http_code
 *
 * @return Array
 */
if (!function_exists('abt_custom')) {
    function abt_custom($error_code, $message, $http_code = 400)
    {
        //$errorMsg = "[ ERR: ".$error_code." ] ".$message;
        $errorMsg = $message . ' ( ' . $error_code . ' )';
        throw new \Exception($errorMsg, $http_code);
    }
}



/**
 * Check value new config if exists then return new config value.
 *
 * @param string $key
 * @param string $defaultValue
 *
 * @return Array
 */
//        $loginView = old_config('bendt-auth.view.login','auth.login');
if (!function_exists('new_config')) {
    function new_config($newConfigKey, $oldConfigKey, $defaultValue)
    {
        if(config($newConfigKey)) return config($newConfigKey, $defaultValue);
        return config($oldConfigKey, $defaultValue);
    }
}
