<?php

namespace App\Http\Middleware;

use Closure;

class SwitchLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        // 检测有没有设置语言的请求参数
        $language = $request->input('language');

        if (!$language) {
            // 从session中去语言配置
            $language = $request->session()->get('language');
        }

        // 若session中没有语言，尝试从浏览器中取得语言参数
        // if (!$language) {
        //     if (preg_match('/([a-z]{2})(?:-([a-z]{2}))?/i', (string) $request->header('accept-language'), $matches)) {
        //         $language = strtolower($matches[1]);
        //         if (isset($matches[2])) {
        //             $language .= '-' . strtoupper($matches[2]);
        //         }
        //     }
        // }

        // 若浏览器中也没有取到，则使用默认英语
        if (!$language) {
            $language = 'zh-CN';
        }

        // 记录语言设置到session中
        $request->session()->put('language', $language);        
        
        // 设置系统语言
        app()->setLocale($language);

        
        return $next($request);
    }
}
