<?php

namespace sisVentas\Http\Controllers\Auth;

use sisVentas\User;
use Validator;
use sisVentas\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/registrados';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function showRegistrationForm()
    {
        return redirect('login');
    }

    public function get_view()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'activated' => 1])) {
            return redirect()->intended('admin/registrar-estudiante');
        } else {
            return redirect()->intended('login')->with('data', ['Nombre de usuario y/o Contraseña Incorrectas']);
        }
    }

    public function logout(Request $request)
    {
        //$this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/login');

    }

}
