<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
* 系统配置
*/
class Config extends Model
{
	
	/**
	 * 类型： 文本
	 */
	const TYPE_TEXT = 'Text';

	/**
	 * 类型：单选项
	 */
	const TYPE_SINGLE_OPTION = 'SingleOption';

	/**
	 * 类型：多选项
	 */
	const TYPE_MULTIPLE_OPTION = 'MultipleOption';

	protected $primaryKey = 'key';
	
	protected $keyType = 'string';


	public $incrementing = false;

	public $timestamps = false;

	protected $casts = [
		'options' => 'array',
	];

	public function getValueAttribute()
	{
		switch (@$this->attributes['type']) {
			case static::TYPE_MULTIPLE_OPTION:
				return (array) json_decode($this->attributes['value']);
			default:
				return @$this->attributes['value'];
		}
	}

	public function setValueAttribute($value)
	{
		if (is_array($value)) {
			$value = json_encode($value);
		}
		$this->attributes['value'] = $value;
	}
}