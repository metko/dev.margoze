<?php

namespace App\Auth;

use App\User\User;
use App\Commune\Commune;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Coderello\Laraflash\Facades\Laraflash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
        //laraflash()->message()->content('Félicitation vous êtes bien inscrit')->title('Yeah!')->type('success');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
            'first_name' => ['required', 'string', 'min:3'],
            'last_name' => ['required', 'string', 'min:3'],
            'commune_id' => ['required'],
            'district_id' => ['required'],
            'date_of_birth' => ['required', 'date'],
            'phone_1' => ['required', 'digits:10'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\User
     */
    /**
     * create
     *
     * @param  mixed $data
     * @param  mixed $request
     *
     * @return void
     */
    protected function create(array $data)
    {
        $username = $data['first_name'].' '.$data['last_name'][0];
        $user = User::create([
            'username' => $username,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'commune_id' => $data['commune_id'],
            'district_id' => $data['district_id'],
            'date_of_birth' => $data['date_of_birth'],
            'phone_1' => $data['phone_1'],
        ]);
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $communes = Commune::all();
        return view('auth.register', compact('communes'));
    }
}
