<?php
namespace App\Http\Controllers\Root;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Auth;

class ArticleController extends Controller
{

	/**
	 * 文章列表
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
		$model = Article::latest();

		// 附加筛选条件。
		foreach ([
			'id',
			'admin_id'
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
		// 模糊搜索
		foreach ([
			'title',
			// 'content' // 取消内容全文搜索
		] as $field) {			
			if ($request->filled($field)) {
				$model->where($field, 'like', '%' . $request->input($field) . '%');
			}
		}

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());

		return view('root.article.list', compact('data'));
	}

	/**
	 * 编辑文章
	 */
	public function getEdit(Request $request)
	{
		// 取得要编辑的文章。
		$data = Article::find($request->id);

		// 返回编辑视图。
		return view('root.article.edit', compact('data'));
	}

	/**
	 * 保存文章
	 */
	public function postSave(Request $request)
	{
		$this->validate($request, [
			'title' => 'required|min:4',
			'content' => 'required',
		]);

		// 取得当前用户。
		$admin = Auth::guard('root')->user();

		// 保存文章。
		$data = Article::findOrNew($request->id);
		$data->admin()->associate($admin);
		
		$data->title = $request->input('title');
		$data->content = $request->input('content');
		$data->save();

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('保存成功。');
	}

	/**
	 * 删除文章
	 */
	public function postDelete(Request $request)
	{
		// 取得要删除的数据。
		$data = Article::find($request->id);
		if ($data) {
			// 删除数据。
			$data->delete();
		}

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('删除成功。');
	}
}
