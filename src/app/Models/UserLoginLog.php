<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Agent;

/**
 * 登陆日志
 *
 * @author  
 *
 */
class UserLoginLog extends Model
{

	protected $appends = [
		'browser',
		'platform'
	];

	protected $casts = [
		'ips' => 'collection'
	];

	public function user()
	{
		return $this->morphTo();
	}

	/**
	 * 浏览器
	 */
	public function getBrowserAttribute()
	{
		Agent::setUserAgent($this->user_agent);
		$browser = Agent::browser();
		$version = Agent::version($browser);
		return $browser . '/' . $version;
	}

	/**
	 * 操作系统
	 */
	public function getPlatformAttribute()
	{
		Agent::setUserAgent($this->user_agent);
		$platform = Agent::platform();
		$version = Agent::version($platform);
		return $platform . '/' . $version;
	}
}
