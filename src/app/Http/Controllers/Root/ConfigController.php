<?php
namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Root\ConfigSave;
use App\Models\Config;
use Cache;

/**
 * 配置管理
 *
 * @author  
 *
 */
class ConfigController extends Controller
{

	/**
	 * 编辑系统配置
	 */
	public function getEdit()
	{
		$data = Config::oldest('weight')->get();
		return view('root.config.edit', compact('data'));
	}

	/**
	 * 保存系统配置
	 */
	public function postSave(ConfigSave $request)
	{
		// 取得要修改的数据。
		$data = Config::all();

		// 验证数据格式。
		$validator = $this->getValidationFactory()->make($request->data, $data->pluck('rules', 'key')->all(), [], $data->pluck('name', 'key')->all());
		if ($validator->fails()) {
			return back()->withInput()->withErrors($validator);
		}

		// 保存修改。
		$data->each(function ($item) use ($request) {
			$item->value = $request->data[$item->key];
			$item->save();
		});

		// 强制刷新缓存。
		Cache::forget('Config');

		// 返回成功信息。
		return redirect()->back()->withMessageSuccess('保存成功。');
	}
}
