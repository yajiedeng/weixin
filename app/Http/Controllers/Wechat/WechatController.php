<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use Log;
use EasyWeChat\Kernel\Messages\Text;

class WechatController extends Controller
{
    //公众号 接收消息&事件地址
    public function serve()
    {
        $app = app('wechat.official_account');
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
//        $service = $app->customer_service;
        $text = new Text('hello');
        return $app->customer_service->message($text)->to($openId)->send();
        return $app->server->serve();
    }
}
