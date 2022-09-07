<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //returns the field name used to login besides the password (by default email)
    public function username()
    {
        $value = request()->input('identify');
        $field = filter_var($value, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        request()->merge([$field => $value]);
        return $field;
    }

    protected function credentials()
    {
        $creds = array_merge(request()->only($this->username(), 'password'), ['status'=>'accepted']);
        return $creds;
    }

    protected function sendFailedLoginResponse()
    {
        $user = User::where($this->username(), request()->{$this->username()})->first();
        if($user && $user->status != 'accepted')
        {
            return redirect()->back()->with('fail_message',trans('trans.your_account_is_not_accepted'));
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
