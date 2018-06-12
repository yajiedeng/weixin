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
    Route::any('/wechat','WechatController@serve'); // 公众号接收消息 & 事件 地址
    Route::get('menus',"MenuController@meunList"); // 查询公众号自定菜单
    Route::post('/menus/add',"MenuController@addMenu"); // 添加公众号自定义菜单
    Route::post('/menus/delete',"MenuController@deleteMenu"); // 删除公众号自定义菜单
    Route::get('/users',"UserController@getUserList"); // 获取关注用户列表
    Route::get('/users/old',"UserController@oldUserInfo"); // 获取关注用户列表
    Route::post('/qrcodes/add',"QrCodeController@createCarQrCodes"); // 获取关注用户列表
    Route::post('/license/log',"QrCodeController@createCarQrCodes"); // 导入车牌号日志



//    Route::any('/miniapp','MiniAppController@serve'); // 小程序接收消息 & 事件 地址
//    Route::post('/program/openid/put','MiniAppController@getOpenid'); // 小程序获取 openid
//    Route::post('/carqrcode/add','MiniAppController@createCarQrCode'); // 小程序生成车辆二维码
//    Route::post('/car/get/plates','MiniAppController@getPlateNumber'); // 小程序获取车牌号
//    Route::post('/user/validate','MiniAppController@userValidation'); // 小程序验证用户证件信息
});

// 七鱼客服
Route::any('/qiyu',"Qiyu\KefuController@serve");

Route::any('test',"Dadao\TestController@test");