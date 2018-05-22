<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class WechatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "哈哈哈哈 您刚才说 ".$message['Content'];
        });

        return $app->server->serve();
    }
}
