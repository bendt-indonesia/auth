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
