<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\OperationRecordObserver;

/**
 * 操作记录
 *
 * @author  
 *
 */
class OperationRecord extends Model
{

	protected $casts = [
		'id' => 'integer',
		'user_id' => 'integer',
		'user_type' => 'string',
		'method' => 'string',
		'path' => 'string',
		'input' => 'collection',
		'user_agent' => 'string',
		'ips' => 'collection',
		'status_code' => 'integer',
		'response' => 'collection',
		'created_at' => 'datetime',
		'updated_at' => 'datetime'
	];

	public static function boot()
	{
		parent::boot();

		static::observe(new OperationRecordObserver());
	}

	/**
	 * 所属用户
	 */
	public function user()
	{
		return $this->morphTo();
	}
}
