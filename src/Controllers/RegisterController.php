<?php

namespace Bendt\Auth\Controllers;

use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\User;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    public function showRegistrationForm()
    {
        return view(new_config('bendt-auth.view.register','bendt-auth.register_view', 'bendt-auth::register'));
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/backend';


    /**
     * Create a new controller instance
     */
    public function __construct()
    {
        $this->redirectTo = config('bendt-auth.redirect_to');

        if(config('bendt-auth.register_enabled')==false) {
            throw new Exception('Register not allowed!');
        }

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = config('bendt-auth.validator.store', [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        return Validator::make($data, $validator);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $fields = config('bendt-auth.fields', ['name','email','password']);
        $userData = [];

        foreach ($fields as $field_name => $field_type) {
            if(isset($data[$field_name])) {
                if($field_type === 'password') {
                    $userData[$field_type] = bcrypt($data[$field_name]);
                } else {
                    $userData[$field_type] = $data[$field_name];
                }
            }
        }
        return User::create($userData);
    }
}
