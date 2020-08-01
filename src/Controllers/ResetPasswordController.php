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

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        if(config('bendt-auth.response.reset.response_type') === 'json') {
            return
                response()->json([
                    'success' => 1,
                    'message' => config('bendt-auth.response.reset.success_msg')
                ], 200);
        } else {
            return redirect($this->redirectPath())->with('status', trans($response));
        }
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        if(config('bendt-auth.response.reset.response_type') === 'json') {
            return
                response()->json([
                    'success' => 0,
                    'message' => config('bendt-auth.response.reset.error_msg')
                ], 422);
        } else {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
        }
    }
}
