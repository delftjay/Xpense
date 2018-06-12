<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserLoginLog;
use App\Models\User;
use Carbon\Carbon;
use Auth;

/**
 * 用户管理
 *
 * @author  
 *
 */
class UserController extends Controller
{

	/**
	 * 用户列表
	 */
	public function getList(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'date' => [
				'nullable',
				'regex:/^\d{4}-\d{2}-\d{2}\s~\s\d{4}-\d{2}-\d{2}$/'
			]
		]);

		// 取得时间范围。
		$date_start = null;
		$date_end = null;
		if ($request->filled('date')) {
			list ($date_start, $date_end) = explode(' ~ ', $request->input('date'));
			$date_start = Carbon::parse($date_start)->startOfDay();
			$date_end = Carbon::parse($date_end)->endOfDay();
		}

		// 取得数据模型。
		$model = User::latest('id');

		// 附加筛选条件。
		foreach ([
			'id',
			'name',
			'mobile',			
		] as $field) {
			if ($request->filled($field)) {
				$value = $request->input($field);
				$model->{is_array($value) ? 'whereIn' : 'where'}($field, $value);
			}
		}
		if ($date_start) {
			$model->where('created_at', '>=', $date_start);
		}
		if ($date_end) {
			$model->where('created_at', '<=', $date_end);
		}

		// $cookies = $request->cookie();dump($cookies);exit;

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());

		return view('root.user.list', compact('data'));
	}

	/**
	 * 用户详情
	 */
	public function getDetail(Request $request)
	{
		$data = User::find($request->input('id'));
		if (is_null($data)) {
			abort(404);
		}
		return view('root.user.detail', compact('data'));
	}

	/**
	 * 登陆日志列表
	 */
	public function getLoginLogList(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'date' => [
				'nullable',
				'regex:/^\d{4}-\d{2}-\d{2}\s~\s\d{4}-\d{2}-\d{2}$/'
			]
		]);

		// 取得时间范围。
		$date_start = null;
		$date_end = null;
		if ($request->filled('date')) {
			list ($date_start, $date_end) = explode(' ~ ', $request->input('date'));
			$date_start = Carbon::parse($date_start)->startOfDay();
			$date_end = Carbon::parse($date_end)->endOfDay();
		}

		// 取得数据模型。
		$model = UserLoginLog::where('user_type', User::class)->with('user')->latest('id');

		// 附加筛选条件。
		foreach ([
			'id',
			'user_id'
		] as $field) {
			if ($request->filled($field)) {
				$value = $request->input($field);
				$model->{is_array($value) ? 'whereIn' : 'where'}($field, $value);
			}
		}
		if ($date_start) {
			$model->where('created_at', '>=', $date_start);
		}
		if ($date_end) {
			$model->where('created_at', '<=', $date_end);
		}

		// 关联表搜索。
		foreach ([
			'name',
			'mobile',			
		] as $field) {
			if ($request->filled($field)) {
				$value = $request->input($field);
				$model->whereHas('user', function ($query) use ($field, $value) {
					$query->where($field, $value);
				});
			}
		}

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 10));

		// 附加翻页参数。
		$data->appends($request->all());

		return view('root.user.login-log.list', compact('data'));
	}

	/**
	 * 伪装登录
	 */
	public function postLogin(Request $request)
	{
		// 取得要伪装的用户。
		$user = User::find($request->id);
		if (is_null($user)) {
			return redirect()->back()->withMessageError('用户不存在。');
		}

		// 伪装登陆。
		Auth::guard('web')->login($user);

		// 跳转到该用户的仪表盘。
		return redirect()->route('home');
	}
}
