<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Cache;

class ValidateSmsVerifyRule implements Rule
{
    
    private $mobile;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($mobile="")
    {
        $this->mobile = $mobile;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $mobile = $this->mobile;
		
		// 允许调试模式下，使用手机号后四位直接过验证。
		// if (config('app.debug') && $value == substr($mobile, -4)) {
		// 	return true;
		// }

		// 从缓存中取出短信验证码。
		$key = 'Verify@smscode:' . $mobile;
		$sms = Cache::get($key);
		
		// 取得校验结果。
		$result = $sms && $sms['code'] == $value;

		// 处理失效。
		Cache::forget($key);

		// 返回结果
		return (bool) $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '验证码不正确';
    }
}
