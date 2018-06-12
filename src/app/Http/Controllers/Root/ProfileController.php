<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth, Hash;

/**
 * 账号信息
 *
 * @author  
 *
 */
class ProfileController extends Controller
{

	/**
	 * 修改密码
	 */
	public function getChangePassword()
	{
		return view('root.profile.change-password');
	}

	/**
	 * 修改密码
	 */
	public function postChangePassword(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'old_password' => 'required',
			'password' => 'required|confirmed|min:8'
		], [
			'old_password.required' => '旧密码不能为空。',
			'password.required' => '新密码不能为空。',
			'password.confirmed' => '密码与确认密码不匹配。',
			'password.min' => '新密码必须至少8个字符。'
		]);

		// 取得当前用户。
		$user = Auth::guard('root')->user();

		// 检查当前密码。
		if (! Hash::check($request->input('old_password'), $user->password)) {
			return redirect()->back()->withErrors([
				'old_password' => '旧密码验证不通过。'
			]);
		}

		// 修改密码。
		$user->password = $request->input('password');
		$user->save();

		// 强制退出。
		Auth::guard('root')->logout();

		// 返回仪表盘。
		return redirect()->route('RootDashboard');
	}
}
