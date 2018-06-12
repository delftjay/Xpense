<?php
namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 保存配置
 *
 * @author  
 *
 */
class ConfigSave extends FormRequest
{

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'data' => [
				'required',
				'array'
			]
		];
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'keys' => '配制项'
		];
	}
}
