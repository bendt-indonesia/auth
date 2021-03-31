<?php
namespace Bendt\Auth\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the form to change the user password.
     */
    public function index(){
        return view('bendt-auth::passwords.update');
    }

    /**
     * Update the password for the user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'old' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::find(Auth::id());
        $hashedPassword = $user->password;

        if (Hash::check($request->old, $hashedPassword)) {
            //Change the password
            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();

            $request->session()->flash('success', 'Your password has been changed.');

            return redirect(config('bendt-auth.redirect_to'));
        }

        $request->session()->flash('failure', 'Your password has not been changed.');

        return back();
    }
}
