<?php
namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 验证登录
 *
 * @author  
 *
 */
class AuthLogin extends FormRequest
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
			'username' => [
				'required',
				'exists:admins,username'
			],
			'password' => [
				'required',
				'min:8'
			],
			'remember_me' => [
				'in:true,false'
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
			'username' => '用户名',
			'password' => '密码',
			'remember_me' => '记住我'
		];
	}
}
