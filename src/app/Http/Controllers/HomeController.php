<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    //
    public function index()
    {
    	return view('index');
    }

    public function home() {
        return redirect('/');
    }

    /**
     * 本地化设置
     * @param  Request $request 
     * @return null           
     */
    public function locale(Request $request)
    {
        $language = $request->language;

        // 设置session
        $request->session()->put('language', $language);

        // 设置系统语言
        app()->setLocale($language);

        return redirect()->back();
    }
}
