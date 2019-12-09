<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    use AuthenticatesUsers;
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:web_admin')->except('logout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Login user.
     *
     * @param  \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $remember = boolval($request->get('remember'));
        $credentials = $this->credentials($request);
        $email = $credentials['email'];
        $password = $credentials['password'];
        if (Auth::guard('web_admin')->attempt(['email' => $email, 'password' => $password, 'isAdmin' => 1], $remember)) {
            return redirect('/');
        }
        if (Auth::attempt($credentials, $remember)) {
            return redirect('/');
        }
        return redirect('/')->withErrors(['errors' => 'Wrong credentials']);
    }

    /**
     * Logoout session.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        Auth::guard('web_admin')->logout();
        return redirect('/');
    }
}
