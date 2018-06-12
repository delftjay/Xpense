<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use App\Rules\ValidatePhoneRule;
use App\Rules\ValidateSmsVerifyRule;

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
    protected $request;
    protected $invite_url = NULL;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest');

        $this->request = $request;
    }

    protected function redirectTo()
    {
        if ($this->invite_url) {
            return $this->invite_url;
        }

        if ($this->request->session()->has('invite_url')) {
            $url = $this->request->session()->get('invite_url');

            $this->request->session()->forget('invite_url');

            return $url;
        }

        return '/';
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
            'name' => [
                'required',
                'string',
                'min:4',
                'max:255',
                Rule::unique('users'),
            ],
            // 'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'mobile' => [
                'required',
                new ValidatePhoneRule,
                Rule::unique('users'),
            ],
            'verify_code' => [
                'required',
                new ValidateSmsVerifyRule($data['mobile']),
            ],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {   
        if ($data['invite_url']) {
            $this->invite_url = $data['invite_url'];
        }
        
        return User::create([
            'name' => $data['name'],            
            'password' => Hash::make($data['password']),
            'mobile' => $data['mobile'],
            'invite_code' => $data['invite_code'],
            'mobile_verified' => true,
        ]);
    }

    public function index(Request $request)
    {
        $data = [
            'invite_url' => $request->invite_url,
        ];

        return view('auth.register', $data);
    }
}
