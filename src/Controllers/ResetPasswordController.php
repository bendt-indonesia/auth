<?php

namespace Bendt\Auth\Controllers;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function showResetForm(Request $request, $token = null)
    {
        return view(config('bendt-auth.reset_view', 'passwords.reset'))->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/backend';

    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        if(config('bendt-auth.forgot_enabled')==false) {
            throw new \Exception('This function is disabled!');
        }
        $this->redirectTo = config('bendt-auth.redirect_to');
        $this->middleware('guest');
    }
}
