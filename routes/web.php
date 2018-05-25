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

Route::get('/', function () {
    return view('welcome');
});
//前台路由组
Route::group(['namespace' => 'Dadao'], function(){
    Route::get('/zhima',"ZhimaController@zhima");
    Route::get('/channel',"ChannelController@create");
    Route::post('/chnnel',"ChannelController@qrCode");
});