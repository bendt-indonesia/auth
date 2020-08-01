<?php

namespace Bendt\Auth\Controllers\API;

use Bendt\Auth\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class AuthController extends ApiController
{

    use SendsPasswordResetEmails;

    public function logout() {
        Auth::user()->token()->revoke();
        return $this->sendResponse(['success'=>true]);
    }

    public function reset() {
        if(config('bendt-auth.forgot_enabled')==false) {
            throw new Exception('This function is disabled!');
        }

        Auth::user()->token()->revoke();
        return $this->sendResponse(['success'=>true]);
    }

}
