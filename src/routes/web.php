<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 验证控制器。

// Authentication Routes...
Route::get('login', 'Auth\LoginController@index');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@index');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ResetPasswordController@index');
Route::post('password/reset', 'Auth\ResetPasswordController@resetPassword');

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@home');
Route::get('locale', 'HomeController@locale');
