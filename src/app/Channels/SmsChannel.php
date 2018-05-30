<?php
namespace App\Channels;

use Illuminate\Notifications\Notification;
use RuntimeException;

/**
 * 短信服务
 *
 */
class SmsChannel
{
	protected $api_url = NULL;
    protected $api_uid = NULL;
    protected $api_key = NULL;

	public function __construct()
	{
		$this->api_url = config('services.sms.api_url');
		$this->api_uid = config('services.sms.api_uid');
		$this->api_key = config('services.sms.api_key');
	}

	/**
	 * 发送给定通知
	 *
	 * @param  mixed  $notifiable
	 * @param  \Illuminate\Notifications\Notification  $notification
	 * @return void
	 */
	public function send($notifiable, Notification $notification)
	{
		if (! $to = $notifiable->routeNotificationFor('sms')) {
			return;
		}
		
		$mobile = $to;
		$message = $notification->toSms($notifiable);
        $content = $message->content;
        $template = '';

        $url = $this->api_url;
        $data = [
        	'ac' => 'send',
        	'uid'=>	$this->api_uid,				
			'pwd'=>	$this->api_key,				
			'mobile'=> $mobile,			
			'content'=>	$content,		
			'template'=> $template,	//变量模板ID 全文模板不用填写
			'format' => 'json',	//接口返回信息格式 json\xml\txt
        ];
        // print_r($this->api_key);exit;
        
        $responseData = $this->Post($url, $data);
        if ($responseData != false) {                        
            if ($responseData['stat'] == '100') {
            	return true;
            }
        }

        return false;
	}

    public function Post($url="", $data=""){
		$data = http_build_query($data);
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data);

		$result = curl_exec( $ch );
		$errno = curl_errno($ch);

		curl_close ( $ch );

		if (!$errno) {
			if( mb_detect_encoding($result, array('ASCII','UTF-8','GB2312','GBK')) != 'UTF-8' ) {
				$result = iconv('GBK','UTF-8',$result);
			}

			return json_decode($result, true);
		}

		return false;
    }
}
