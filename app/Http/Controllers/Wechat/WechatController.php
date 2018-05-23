<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;

class WechatController extends Controller
{
    //公众号 接收消息&事件地址
    public function serve()
    {
        $app = app('wechat.official_account');

        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
        $text = new Text('hello');
        $app->customer_service->message($text)->to($openId)->send();
        return $app->server->serve();

        $app->server->push(function ($message) {
            // $message['FromUserName'] // 用户的 openid
            // $message['MsgType'] // 消息类型：event, text....
            return "hello";
        });
        return $app->server->serve();
    }
}
