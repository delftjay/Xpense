<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Auth;

/**
 * 首页
 *
 * @author  
 *
 */
class HomeController extends Controller
{

	/**
	 * 仪表盘
	 */
	public function getDashboard()
	{						
		// 取出当前用户的POST操作记录列表。
		$operation_records = Auth::guard('root')->user()
			->operationRecords()
			->select('id', 'route_name', 'user_agent', 'ips', 'created_at')
			->where('method', 'post')
			->latest()
			->take(10)
			->get();

		return view('root.dashboard')->with(compact('operation_records'));
	}
}
