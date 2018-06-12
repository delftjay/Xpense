<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/';
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');

        $this->request = $request;
    }

    public function username()
    {
        return 'name';
    }

    protected function redirectTo()
    {   
        if ($this->request->session()->has('invite_url')) {
            $url = $this->request->session()->get('invite_url');

            $this->request->session()->forget('invite_url');

            return $url;
        }

        return '/';
    }

    public function index(Request $request)
    {   
        $invite_url = '';
        $previous_url = url()->previous();
        
        if (strpos($previous_url, 'invite') > 0) {
            $request->session()->put('invite_url', $previous_url);        

            $invite_url = urlencode($previous_url);
        }

        $data = [
            'invite_url' => $invite_url,
        ];

        return view('auth.login', $data);        
    }
}
