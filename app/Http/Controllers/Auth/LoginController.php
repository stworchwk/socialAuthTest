<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($social)
    {
        return $social == 'facebook' ? Socialite::driver('facebook')->redirect() : Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback($social)
    {
        if ($social == 'facebook') {
            $socialUser = Socialite::driver('facebook')->user();
            $facebookId = $socialUser->getId();
        } else {
            $socialUser = Socialite::driver('google')->user();
            $googleId = $socialUser->getId();
        }

        $user = User::where('facebook_id', $socialUser->getId())->orWhere('google_id', $socialUser->getId())->first();
        if (!$user) {
            $user = User::firstOrNew(['full_name' => $socialUser->getName()]);
            $user->full_name = $socialUser->getName();
            $user->facebook_id = isset($facebookId) ? $facebookId : $user->facebook_id;
            $user->google_id = isset($googleId) ? $googleId : $user->google_id;
            if ($user->save()) {
                return $this->socialAuthLogin($user->id);
            } else {
                return redirect(route('login'))->with('loginError', 'ระบบมีปัญหา โปรดลองอีกครั้งภายหลัง!');
            }
        } else {
            return $this->socialAuthLogin($user->id);
        }
    }

    protected function socialAuthLogin($user_id)
    {
        if (Auth::loginUsingId($user_id)) {
            return redirect(route('home'));
        } else {
            return redirect(route('login'))->with('loginError', 'ระบบมีปัญหา โปรดลองอีกครั้งภายหลัง!');
        }
    }
}
