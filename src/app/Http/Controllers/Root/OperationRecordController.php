<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OperationRecord;
use App\Models\Admin;
use Carbon\Carbon;
use Route;

/**
 * 操作记录管理
 *
 * @author  
 *
 */
class OperationRecordController extends Controller
{

	/**
	 * 操作记录列表
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
		$model = OperationRecord::with('user')->latest()->where('user_type', Admin::class);

		// 附加筛选条件。
		foreach ([
			'id',
			'user_id',
			'method',
			'route_name'
		] as $field) {
			if ($request->filled($field)) {
				$data = $request->input($field);
				if (is_array($data)) {
					$model->whereIn($field, $data);
				} else {
					$model->where($field, $data);
				}
			}
		}
		if ($date_start) {
			$model->where('created_at', '>=', $date_start);
		}
		if ($date_end) {
			$model->where('created_at', '<=', $date_end);
		}

		// 取得单页数据。
		$data = $model->simplePaginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());

		// 系统中的所有管理员列表。
		$users = Admin::all('id', 'username');

		// 取得系统控制器列表。
		$routes = collect();
		foreach (Route::getRoutes() as $route) {
			$name = $route->getName();
			$middleware = (array) array_get($route->getAction(), 'middleware');
			if (! is_null($name) && in_array('permission:root', $middleware)) {
				$routes[$name] = trans('routes.' . $name);
				$routes[$name] = preg_replace('/^routes\./', '', $routes[$name]);
			}
		}
		$routes = $routes->sort();

		return view('root.audit.operation-record.list', compact('data', 'users', 'routes'));
	}

	/**
	 * 操作记录详情
	 */
	public function getDetail(Request $request)
	{
		$data = OperationRecord::find($request->input('id'));
		if (is_null($data)) {
			abort(404);
		}

		return view('root.audit.operation-record.detail', compact('data'));
	}

	/**
	 * 响应内容查看
	 */
	public function getResponseView(Request $request)
	{
		// 取出记录。
		$data = OperationRecord::find($request->input('id'), [
			'response'
		]);
		if (is_null($data)) {
			abort(404);
		}

		// 取出响应的页面。
		$content = $data->response['content'];

		// 注释掉302跳转代码。
		$content = preg_replace('#\<meta\s+http-equiv="refresh"\s+content="[^"]+"\s*/?\>#i', '<!-- $0 -->', $content);

		return view('root.audit.operation-record.response-view', compact('content'));
	}
}
