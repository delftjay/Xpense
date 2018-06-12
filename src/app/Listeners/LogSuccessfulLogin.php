<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Models\UserLoginLog;

class LogSuccessfulLogin
{
    protected $request;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        // 记录登陆日志。
        $log = new UserLoginLog();
        $log->user()->associate($event->user);
        $log->user_agent = $this->request->header('user-agent');
        $log->ips = $this->request->getClientIps();
        $log->save();
    }
}
