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

Route::get('/','IndexController@index');
Route::get('/home','IndexController@index')->name('home');
Route::namespace('Auth')->group(function(){
  //注册 路由
  Route::get('register','RegisterController@showRegistrationForm');
  Route::post('register','RegisterController@register')->middleware('mail');
  //登录
  Route::get('login','LoginController@showLoginForm')->name('login');
  Route::post('login','LoginController@login');
  //退出
  Route::get('logout','LoginController@logout');
});
Route::middleware('auth')->group(function (){
	//申请路由
	Route::get('/jie','ProController@jie');
	Route::post('/jie','ProController@jiePost');

	//投资路由
	Route::get('touzi/{pid}','ProController@touzi');
	Route::post('touzi/{pid}','ProController@touziPost');
	//打款
	Route::get('grow','GrowController@grow');
	Route::get('myzd','ProController@myzd');
	Route::get('mysy','ProController@mysy');
	Route::get('mytz','ProController@mytz');
	Route::post('pay','ProController@pay');
	Route::get('captcha','ProController@captcha');
});

//资源控制器
Route::resource('cart','CartController');
//审核列表路由
Route::get('prolist','CheckController@prolist');
//审核路由
Route::get('check/{pid}','CheckController@check');
Route::post('check/{pid}','CheckController@checkPost');
// Route::get('test',['middleware'=>'App\Http\Middleware\EmailMiddleware',function (){
// 	dump("nihao");
// }]);