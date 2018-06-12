<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 身份验证信息
 *
 * @author  
 *
 */
class Authentication extends Model
{

	/**
	 * 验证类型：中国大陆身份证
	 */
	const TYPE_IDENTITY_CARD = 'IdentityCard';

	/**
	 * 验证类型：护照
	 */
	const TYPE_PASSPORT = 'Passport';

	/**
	 * 验证类型：驾照
	 */
	const TYPE_DRIVER_LICENSE = 'DriverLicense';

	protected $casts = [
		'extra' => 'collection'
	];

	/**
	 * 所属用户
	 */
	public function user()
	{
		return $this->hasOne(User::class);
	}
}
