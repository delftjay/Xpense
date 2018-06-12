<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Airdrop;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index()
    {        
        return view('invite.index');	
    }

    public function share() 
    {
        return view('invite.share');    
    }

    public function save(Request $request) 
    {        

        $messages = [
            'token.required' => 'ERROR! Please enter a valid ETH wallet address',
            'token.max' => 'ERROR! Please enter a valid ETH wallet address',
            'qq_ticket.required' => 'ERROR! DO NOT SPAM',
            'qq_randstr.required' => 'ERROR! DO NOT SPAM',
        ];

        $this->validate($request, [
            'token' => 'required|max:100',
            'qq_ticket' => 'required',
            'qq_randstr' => 'required',
        ], $messages);

        if (!$this->qq_verify($request->qq_ticket, $request->qq_randstr)) {
            return $this->out([], 40011, 'qq_verify err');
        }

        $invcode = "";
        $airdrop = Airdrop::where('token', $request->token)->first();
        if ($airdrop) {
            $invcode = $airdrop->code;
        } else {
            // 新增
            $invcode = $this->get_invite_code() . strtoupper(substr($request->token, -2));
            $insert_data = [
                'user_id' => Auth::id(),
                'token' => $request->token,
                'code' => $invcode,
                'from' => $request->fromCode,
                'ip' => $request->getClientIp(),
            ];

            Airdrop::create($insert_data);
        }

        $data = [
            'InviteCode' => $invcode,
        ];

        return $this->out($data);
    }

    public function req(Request $request) 
    {
        if ($request->code) {
            $airdrop = Airdrop::where('code', $request->code)->first();

            if ($airdrop) {
                $data = [
                    'count' => $airdrop->count,
                    'invite' => $airdrop->invite,
                ];

                return $this->out($data);
            }
        } 

        return $this->out([], 40012, '缺水code信息');
    }

    protected function out($data=[], $error=0, $message="") 
    {
        $output = [
            'result' => $data,
            'error' => $error,
            'message' => $message,
        ];

        $content = json_encode($output);
        $status = 200;
        $type = 'application/json; charset=utf-8';

        return response($content, $status)->header('Content-Type', $type);
    }

    protected function qq_verify($ticket, $randstr)
    {
        $url = 'https://ssl.captcha.qq.com/ticket/verify';
        $fields = array('aid' => '2014284392',
            'AppSecretKey' => '0SGlXI4WfD-rY4naz52xuhg**',
            'Ticket' => $ticket,
            'Randstr' => $randstr,
            'UserIP' => $_SERVER['REMOTE_ADDR'],
        );

        $fields_string = "";
        //url-ify the data for the POST
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);

        if ($result === FALSE) {            
            return false;
        }

        //echo($result);
        $js = json_decode($result, true);
        if ($js['response'] == 1) {
            return true;
        }

        return false;
    }

    protected function get_invite_code()
    {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0, 25)]
            . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5)
            . substr(microtime(), 2, 5)
            . sprintf('%02d', rand(0, 99));
        for (
            $a = md5($rand, true),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord($a[$f]),
            $d .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F],
            $f++
        ) ;
        return $d;
    }
}
