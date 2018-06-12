<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Airdrop;

/**
 * 空投管理
 *
 * @author  
 *
 */
class AirdropController extends Controller
{

	/**
	 * 空投列表
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
		$model = Airdrop::latest('id');

		// 附加筛选条件。
		foreach ([
			'token',
			'from',
			'tg_name',			
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

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());

		return view('root.airdrop.list', compact('data'));
	}

	/**
	 * 空投详情
	 */
	public function getDetail(Request $request)
	{
		$data = Airdrop::find($request->input('id'));
		if (is_null($data)) {
			abort(404);
		}
		return view('root.airdrop.detail', compact('data'));
	}
}
