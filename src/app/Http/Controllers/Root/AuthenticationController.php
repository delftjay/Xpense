<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Http\Requests\Root\AuthenticationSave;
use Illuminate\Http\Request;
use App\Models\Authentication;
use Carbon\Carbon;

/**
 * 身份验证
 *
 * @author  
 *
 */
class AuthenticationController extends Controller
{

	/**
	 * 验证信息列表
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
		$model = Authentication::latest('id');
		$model->with('user');

		// 附加筛选条件。
		foreach ([
			'id',
			'type'
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
			'username',
			'mobile'
		] as $field) {
			if ($request->filled($field)) {
				$value = $request->input($field);
				$model->whereHas('user', function ($model) use ($field, $value) {
					$model->{is_array($value) ? 'whereIn' : 'where'}($field, $value);
				});
			}
		}

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());
		
		return view('root.authentication.list', compact('data'));
	}

	/**
	 * 身份验证图片
	 */
	public function showVerificationImage(Request $request)
	{
		// 取得验证信息。
		$authentication = Authentication::find($request->id);

		// 取得验证图片。
		if (is_null($authentication) || empty($authentication->extra['image'])) {
			abort(404);
		}
		$image = $authentication->extra['image'];
		$mime = $authentication->extra['mime'];

		// 返回图片。
		return response()->file(storage_path('app/' . $image), [
			'Content-type' => $mime
		]);
	}

	/**
	 * 编辑验证信息
	 */
	public function getEdit(Request $request)
	{
		// 取得要编辑的数据。
		$data = Authentication::find($request->id);
		if (is_null($data)) {
			abort(404);
		}

		// 返回编辑视图。
		return view('root.authentication.edit', compact('data'));
	}

	/**
	 * 保存编辑
	 */
	public function postSave(AuthenticationSave $request)
	{
		// 取得要编辑的数据。
		$data = Authentication::findOrFail($request->id);

		// 编辑数据。
		$data->realname = $request->realname;
		$data->id_number = $request->id_number;
		$data->save();

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('保存成功。');
	}

	/**
	 * 删除验证信息
	 */
	public function postDelete(Request $request)
	{
		// 取得要删除的数据。
		$data = Authentication::find($request->id);
		if ($data) {

			// 删除所属用户的验证状态。
			$data->user->authentication_id = 0;
			$data->user->save();

			// 删除数据。
			$data->delete();
		}

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('删除成功。');
	}
}
