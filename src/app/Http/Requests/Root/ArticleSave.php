<?php
namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

class ArticleSave extends FormRequest
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
		$rules = [];
		$languages = [
			'en',
			'zh-CN',
			'zh-HK'
		];
		foreach ($languages as $language) {
			$rules['title_' . $language] = 'required_without_all:' . collect($languages)->filter(function ($item) use ($language) {
				return $item != $language;
			})->map(function ($item) {
				return 'title_' . $item;
			})->implode(',');
			$rules['content_' . $language] = 'required_without_all:' . collect($languages)->filter(function ($item) use ($language) {
				return $item != $language;
			})->map(function ($item) {
				return 'content_' . $item;
			})->implode(',');
		}
		return $rules;
	}

	/**
	 * Get custom attributes for validator errors.
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'title_en' => '标题（英文）',
			'content_en' => '内容（英文）',
			'title_zh-CN' => '标题（简体中文）',
			'content_zh-CN' => '内容（简体中文）',
			'title_zh-HK' => '标题（繁体中文）',
			'content_zh-HK' => '内容（繁体中文）'
		];
	}
}
