<?php
use Latrell\QQWry\QQWryException;
use App\Models\Config;
use Carbon\Carbon;

if (! function_exists('C')) {

	/**
	 * 读取系统配置
	 */
	function C($key = null, $default = null)
	{
		static $cache = [];
		if (! array_key_exists($key, $cache)) {
			$config = Config::select('type', 'value')->where('key', $key)->first();
			$cache[$key] = $config ? $config->value : null;
		}
		return is_null($cache[$key]) ? $default : $cache[$key];
	}
}


if (! function_exists('ip_query')) {

	/**
	 * IP地址查询
	 */
	function ip_query($ip)
	{
		try {
			$result = QQWry::query($ip)->implode(' ');
		} catch (QQWryException $e) {
			$result = $e->getMessage();
		}

		return $result;
	}
}

if (! function_exists('has_permission')) {

	/**
	 * 检查用户是否拥有指定权限
	 */
	function has_permission($user, $permission)
	{
		foreach ($permission->actions as $action) {
			if ($user->denies($action)) {
				return false;
			}
		}
		return true;
	}
}

if (! function_exists('has_role')) {

	/**
	 * 检查用户是否拥有指定角色
	 */
	function has_role($user, $role)
	{
		foreach ($role->permissions as $permission) {
			if (! has_permission($user, $permission)) {
				return false;
			}
		}
		return true;
	}
}

if (! function_exists('sub_admin')) {

	/**
	 * 检查一个用户是否拥是另一个用户的子用户
	 */
	function sub_admin($user, $sub_admin)
	{
		foreach ($sub_admin->actions as $action) {
			if ($user->denies($action)) {
				return false;
			}
		}
		return true;
	}
}

if (! function_exists('create_unique_id')) {

	/**
	 * 创建一个拥有自校验能力的分布式唯一ID
	 *
	 * 开头4位，用于区分不同服务产生的ID。
	 * 最后2位为校验码，用于校验订ID是否正确。
	 *
	 * @param string 前缀，必须是4个英文字母。
	 * @return string 总长度为30位
	 */
	function create_unique_id($prefix)
	{
		// 生成分布式唯一ID。
		$uniqid = uniqid(gethostname(), true);
		$md5 = substr(md5($uniqid), 12, 8); // 8位md5
		$uint = hexdec($md5);
		$uniqid = $prefix . sprintf('%s%010u', date('YmdHis'), $uint);

		// 校验码为前28位ID的平均值。
		$ckc = 0;
		for ($i = 0, $len = strlen($uniqid); $i < $len; $i ++) {
			$ckc += base_convert(substr($uniqid, $i, 1), 36, 10);
		}
		// 取余得到固定两位的校验码。
		$ckc %= base_convert('zz', 36, 10);
		$ckc = sprintf('%02s', base_convert($ckc, 10, 36));

		// 最终ID。
		$uniqid = strtoupper($uniqid . $ckc);
		return $uniqid;
	}
}

if (! function_exists('check_unique_id')) {

	/**
	 * 校验分布式唯一ID的正确性
	 *
	 * 校验该ID的格式是否正确。
	 *
	 * @return boolean
	 */
	function check_unique_id($id)
	{
		$ckc_1 = substr((string) $id, - 2);

		// 取得ID前28位的平均值。
		$uniqid = substr($id, 0, 28);
		$ckc_2 = 0;
		for ($i = 0, $len = strlen($uniqid); $i < $len; $i ++) {
			$ckc_2 += base_convert(substr($uniqid, $i, 1), 36, 10);
		}
		// 取余得到固定两位的校验码。
		$ckc_2 %= base_convert('zz', 36, 10);
		$ckc_2 = sprintf('%02s', base_convert($ckc_2, 10, 36));

		return strtoupper($ckc_1) == strtoupper($ckc_2);
	}
}

if (! function_exists('opcode')) {

	/**
	 * 生产一个操作验证码，方式重复操作。
	 */
	function opcode($key)
	{
		$code = str_random();
		$expires_at = Carbon::now()->addMinutes(10);
		Cache::put(Auth::id() . '-' . $key, $code, $expires_at);
		return $code;
	}
}
