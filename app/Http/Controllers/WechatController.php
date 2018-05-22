<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class WechatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

//        $wechat = app('wechat.official_account');
////        dump($wechat);
//        $wechat->server->setMessageHandler(function($message){
//            return "欢迎关注 overtrue！";
//        });
//
//        //3d779bed0bc68ec450749709e9c4324d
//
//        Log::info('return response.');
//
//        return $wechat->server->serve();

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 您刚才说 ".$message;
        });

        return $app->server->serve();
    }
}
