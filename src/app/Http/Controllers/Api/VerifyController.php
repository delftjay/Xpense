<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Api\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendMobileVerify;
use App\Notifications\MobileVerify;

// use App\Http\Requests\SendEmailVerify;
// use App\Notifications\EmailVerify;

use Carbon\Carbon;
use Notification;
use Cache;

/**
 * 验证码
 *
 * @author
 *
 */
class VerifyController extends Controller
{
	use ApiResponse;	

	protected function sendFailed(Request $request)
	{
		$msg = $request['errors'];
        $code = $request['code'];

        return $this->failed($msg, $code);
	}
	
	/**
	 * 发送手机短信验证码
	 */
	public function mobile(SendMobileVerify $request)
	{		
		$cooldown = 60; // 验证码发送冷却间隔。
        $mobile = $request->mobile;        
        $key = 'Verify@smscode:' . $mobile;

        if (($sms = Cache::get($key)) && ($seconds = $sms['created_at']->diffInSeconds(null, false)) < $cooldown) {
            // 返回剩余冷却时间。
            return $this->responseCooldown($cooldown - $seconds);
        }

        // 创建验证码。
        $sms = [
            'code' => sprintf('%04d', mt_rand(0, 9999))
        ];
        
        Notification::route('sms', $mobile)->notify(new MobileVerify($sms['code']));

        // 将验证码放入缓存中
        $sms['created_at'] = Carbon::now();
        $expires_at = Carbon::now()->addMinutes(5); // 过期时间为 5 分钟。
        Cache::put($key, $sms, $expires_at);

        return $this->responseCooldown($cooldown);
	}	

	/**
	 * 发送邮件验证码
	 */
	// public function email(SendEmailVerify $request)
	// {
	// 	$cooldown = 60; // 验证码发送冷却间隔。

	// 	$key = 'Verify@emailcode:' . md5($request->email);

	// 	// 检测间隔时间。
	// 	if (($email = Cache::get($key)) && ($seconds = $email['created_at']->diffInSeconds(null, false)) < $cooldown) {
	// 		// 返回剩余冷却时间。
	// 		return $cooldown - $seconds;
	// 	}

	// 	// 创建验证码。
	// 	$email = [
	// 		'code' => sprintf('%06d', mt_rand(0, 999999))
	// 	];

	// 	// 发送邮件验证码。
	// 	Notification::route('mail', $request->email)->notify(new EmailVerify($email['code']));

	// 	// 将验证码放入缓存中。
	// 	$email['created_at'] = Carbon::now();
	// 	$expires_at = Carbon::now()->addMinutes(30); // 过期时间为 30 分钟。
	// 	Cache::put($key, $email, $expires_at);

	// 	return $cooldown;
	// }
	
	protected function responseCooldown(int $seconds) {
		return $this->success(['cooldown' => $seconds]);
	}
}
