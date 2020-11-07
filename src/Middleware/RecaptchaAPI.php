<?php
/*
 *
  ____                 _ _     _____           _                       _
 |  _ \               | | |   |_   _|         | |                     (_)
 | |_) | ___ _ __   __| | |_    | |  _ __   __| | ___  _ __   ___  ___ _  __ _
 |  _ < / _ \ '_ \ / _` | __|   | | | '_ \ / _` |/ _ \| '_ \ / _ \/ __| |/ _` |
 | |_) |  __/ | | | (_| | |_   _| |_| | | | (_| | (_) | | | |  __/\__ \ | (_| |
 |____/ \___|_| |_|\__,_|\__| |_____|_| |_|\__,_|\___/|_| |_|\___||___/_|\__,_|

 Please don't modify this file because it may be overwritten when re-generated.
 */

namespace Bendt\Auth\Middleware;

use Closure;

class RecaptchaAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @throws \Exception
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('bendt-auth.recaptcha', false)) return $next($request);

        $captcha = $request->input('g-recaptcha-response');
        $path = $request->path();
        $grant_type = $request->input('grant_type');

        if ($captcha) {
            $secret = config('bendt-auth.recaptcha_secret', '');
            $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha;
            if (ini_get('allow_url_fopen')) {
                $verifyResponse = file_get_contents($url);
            } else if (function_exists('curl_version')) {
                $verifyResponse = $this->url_get_contents($url);
            } else {
                abt_custom('Oops!', 'Unable to communicate with Google Recaptcha');
            }
            $responseData = json_decode($verifyResponse);
            if (!$responseData->success) {
                return abt_custom('Oops!', 'We are unable to verify you are a human or a bot.');
            }
        } else if ($grant_type === 'refresh_token' && $path === 'oauth/token') {
            return $next($request);
        } else {
            return abt_custom('Oops!', 'We are unable to verify you are a human or a bot.');
        }

        return $next($request);
    }

    private function url_get_contents($url)
    {
        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
