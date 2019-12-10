<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return bool
     */
    protected function create(array $data)
    {
        $user = User::where('email', $data['email'])->first();
        if (!is_null($user) && !is_null($user->password)) {
            return false;
        }

        if (is_null($user)) {
            User::create([
                'firstName' => $data['firstName'],
                'lastName' => $data['lastName'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        } else {
            $user->password = Hash::make($data['password']);
            $user->save();
        }
        return true;
    }

    /**
     * Register user.
     *
     * @param  \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        $credentials = $request->validated();
        $success = $this->create($credentials);
        if ($success) {
            return redirect('/')->with('success', 'Successfully registered');
        }
        return redirect('/')->withErrors(['errMessage' => 'Wrong cridentials']);
    }

    public function show()
    {
        if (config('auth.REGISTRATION_IS_OPEN')){
            return view('register');
        }
        return redirect('/')->withErrors(['message' => 'Registration is disabled']);
    }
}
