<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;
use Log;
class MiniAppController extends Controller
{
    //小程序 客服接收消息&事件地址
    public function serve()
    {
//        Log::info();
        $app = app('wechat.mini_program');
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
        $text = new Text('hello');
        return $app->customer_service->message($text)->to($openId)->send();
        return $app->server->serve();
    }
}
