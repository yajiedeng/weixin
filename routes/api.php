<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//微信路由组
Route::group(['namespace' => 'Wechat'], function(){
    Route::any('/wechat','WechatController@serve');
    Route::any('/miniapp','MiniAppController@serve');
    Route::post('/program/openid/put','MiniAppController@getOpenid');
    Route::post('/carqrcode/add','MiniAppController@createCarQrCode');
    Route::post('/car/get/plates','MiniAppController@getPlateNumber');
});

Route::any('test',"Dadao\TestController@test");
Route::get('menus',"Wechat\MenuController@meunList");
Route::post('menus/add',"Wechat\MenuController@addMenu");
Route::post('menus/delete',"Wechat\MenuController@deleteMenu");