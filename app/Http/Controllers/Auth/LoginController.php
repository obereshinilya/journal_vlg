<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Cache;
use App\Models\User;


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

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    // Блокировка при неудачном вводе пароля
    protected $maxAttempts = 15;  // количество неудачных попыток
    protected $decayMinutes = 1; // время блокировки в минутах

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'name';
    }

    //Логирование входа в систему с записью в БД
    public function loger_in($request)
    {
        $post = new Logs(array(
            'description' => 'Пользователь залогинен',
            'username' => $request->input($this->username()),
            'ip' => $request->ip()
        ));
        $post->save();
    }

    //Логирование выхода в систему с записью в БД
    public function loger_out($request)
    {
        $post = new Logs(array(
            'description' => 'Пользователь вышел из системы',
            'username' => $request->input($this->username()),
            'ip' => $request->ip()
        ));
        $post->save();
    }

    public function loger_error($request)
    {
        $post = new Logs(array(
            'description' => 'Пользователь ввел неверный пароль',
            'username' => $request->input($this->username()),
            'ip' => $request->ip()
        ));
        $post->save();
    }

    public function logout(Request $request)
    {
//        Cache::forget('user-is-online-' . Auth::user()->id);
//        Auth::logout();
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return Redirect::to('/');
    }
}

