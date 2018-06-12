<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Auth;

/**
 * 角色管理
 *
 * @author  
 *
 */
class RoleController extends Controller
{

	/**
	 * 角色列表
	 */
	public function getList(Request $request)
	{
		// 取得数据模型。
		$model = Role::latest('id');

		// 附加筛选条件。
		foreach ([
			'id',
			'name',
			'remark',
			'created_at'
		] as $field) {
			if ($request->filled($field)) {
				$model->where($field, $request->input($field));
			}
		}

		// 附加搜索条件。
		if ($request->filled('keyword')) {
			$keyword = trim((string) $request->input('keyword'));
			$model->where(function ($q) use ($keyword) {
				if (preg_match('/^[-+]?[0-9]+$/', $keyword)) {
					$int_keyword = (int) $keyword;
					$q->orWhere('id', 'like', "%{$int_keyword}%");
				}
				if (preg_match('/^[.,0-9]+$/', $keyword)) {
					$float_keyword = (float) str_replace(',', '', $keyword);
					// noop.
				}
				if (preg_match('/^[- :0-9]+$/', $keyword)) {
					$q->orWhere('created_at', 'like', "%{$keyword}%");
				}
				$keyword = preg_replace('/\s+/', '%', $keyword);
				$q->orWhere('name', 'like', "%{$keyword}%");
				$q->orWhere('remark', 'like', "%{$keyword}%");
			});
		}

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());

		return view('root.role.list', compact('data'));
	}

	/**
	 * 编辑角色
	 */
	public function getEdit(Request $request)
	{
		// 取得要编辑的角色。
		$data = Role::find($request->input('id'));		

		// 取得系统权限列表。
		$permissions = Permission::oldest('group')->oldest('key')->get();		

		// 返回编辑视图。
		return view('root.role.edit', compact('data', 'permissions'));
	}

	/**
	 * 保存编辑
	 */
	public function postEdit(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'name' => 'required|unique:roles,name,' . $request->input('id', 0),
			'permissions' => 'required|array|exists:permissions,key'
		]);

		// 取得要编辑的模型。
		$data = $request->filled('id') ? Role::find($request->input('id')) : null;
		if (is_null($data)) {
			$data = new Role();
		}

		// 取得当前登陆用户。
		$user = Auth::guard('root')->user();

		// 检查当前用户是否有编辑该角色的权限。
		if ($data->exists && ! has_role($user, $data)) {
			abort(403);
		}

		// 编辑数据。
		$data->name = $request->name;
		$data->remark = $request->remark ?: '';
		$data->save();

		// 绑定权限列表。
		$data->permissions()->sync($request->permissions);

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('保存成功。');
	}

	/**
	 * 删除角色
	 */
	public function postDelete(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'id' => 'required|exists:roles'
		]);

		// 取得要删除的角色。
		$data = Role::find($request->input('id'));
		if ($data) {

			// 取得当前登陆用户。
			$user = Auth::guard('root')->user();

			// 检查当前用户是否有编辑该角色的权限。
			if (! has_role($user, $data)) {
				abort(403);
			}

			// 删除角色。
			$data->delete();
		}

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('删除成功。');
	}
}
