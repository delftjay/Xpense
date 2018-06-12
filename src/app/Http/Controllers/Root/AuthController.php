<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Root\AuthLogin;
use Auth;

/**
 * 用户验证
 *
 * @author  
 *
 */
class AuthController extends Controller
{

	public function __construct()
	{
		$this->middleware('json')->only('ajaxForget');
	}

	/**
	 * 登陆页面
	 */
	public function getLogin()
	{
		return view('root.auth.login');
	}

	/**
	 * 登录处理
	 */
	public function postLogin(AuthLogin $request)
	{
		// 登录验证。
		if (! Auth::guard('root')->attempt([
			'username' => $request->input('username'),
			'password' => $request->input('password')
		], $request->input('remember_me', 'false') == 'true')) {
			return redirect()->back()->withErrors('用户名与密码不匹配。');
		}

		// 登录成功
		return redirect()->intended();
	}

	/**
	 * 退出登录状态
	 */
	public function anyLogout()
	{
		// 退出系统。
		Auth::guard('root')->logout();
		return redirect()->route('RootDashboard');
	}
}
