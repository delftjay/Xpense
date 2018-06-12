<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Auth;

/**
 * 管理员管理
 *
 * @author  
 *
 */
class AdminController extends Controller
{

	/**
	 * 用户列表
	 */
	public function getList(Request $request)
	{
		// 取得数据模型。
		$model = Admin::oldest('id');

		// 附加筛选条件。
		foreach ([
			'id',
			'username'
		] as $field) {
			if ($request->filled($field)) {
				$value = $request->input($field);
				$model->{is_array($value) ? 'whereIn' : 'where'}($field, $value);
			}
		}

		// 取得单页数据。
		$data = $model->paginate($request->cookie('limit', 15));

		// 附加翻页参数。
		$data->appends($request->all());

		return view('root.admin.list', compact('data'));
	}

	/**
	 * 编辑用户
	 */
	public function getEdit(Request $request)
	{
		// 取得要编辑的权限。
		$data = Admin::find($request->input('id'));

		// 取得当前用户。
		$admin = Auth::guard('root')->user();

		// 取得系统角色列表。
		$roles = Role::all();

		// 取得系统权限列表。
		$permissions = Permission::oldest('group')->oldest('key')->get();

		// 返回编辑视图。
		return view('root.admin.edit', compact('data', 'roles', 'permissions'));
	}

	/**
	 * 保存编辑
	 */
	public function postEdit(Request $request)
	{
		// 验证输入。
		$validator = $this->getValidationFactory()->make($request->all(), [
			'username' => 'required|unique:admins,username,' . $request->input('id', 0),
			'roles' => 'array|exists:roles,id',
			'permissions' => 'array|exists:permissions,key'
		]);
		$validator->sometimes('password', 'min:8|confirmed', function ($input) {
			return ! $input->id;
		});
		$validator->sometimes('roles', 'required', function ($input) {
			return (int) $input->id !== 1;
		});
		if ($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		}

		// 取得要编辑的模型。
		$data = $request->filled('id') ? Admin::find($request->input('id')) : null;
		if (is_null($data)) {
			$data = new Admin();
		}

		// 取得当前登陆用户。
		$admin = Auth::guard('root')->user();

		// 检查当前用户是否有编辑该用户的权限。
		if ($data->exists && ! sub_admin($admin, $data)) {
			abort(403);
		}
		if ($data->id !== 1) {
			foreach (Role::whereIn('id', (array) $request->input('roles'))->get()->merge($data->roles) as $role) {
				if (! has_role($admin, $role)) {
					abort(403);
				}
			}
			foreach (Permission::whereIn('key', (array) $request->input('permissions'))->get()->merge($data->permissions) as $permission) {
				if (! has_permission($admin, $permission)) {
					abort(403);
				}
			}
		}

		// 编辑数据。
		$data->username = $request->input('username');
		if ($request->filled('password')) {
			$data->password = $request->input('password');
		}
		$data->save();

		if ($data->id !== 1) {

			// 绑定角色列表。
			$data->roles()->sync((array) $request->input('roles'));

			// 绑定权限列表。
			$data->permissions()->sync((array) $request->input('permissions'));
		}

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('保存成功。');
	}

	/**
	 * 删除用户
	 */
	public function postDelete(Request $request)
	{
		// 验证输入。
		$this->validate($request, [
			'id' => 'required|exists:admins|not_in:1'
		]);

		// 取得要删除的用户。
		$data = Admin::find($request->input('id'));
		if ($data) {

			// 取得当前登陆用户。
			$admin = Auth::guard('root')->user();

			// 检查当前用户是否有编辑该用户的权限。
			if (! sub_admin($admin, $data)) {
				abort(403);
			}

			// 删除用户。
			$data->delete();
		}

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('删除成功。');
	}
}
