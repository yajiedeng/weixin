<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;
use EasyWeChat\Kernel\Messages\Text;

class WechatController extends Controller
{
    public function serve()
    {
        $app = app('wechat.mini_program');
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
//        $service = $app->customer_service;
        $text = new Text('您好！overtrue。');

        $app->customer_service->message($text)->to($openId)->send();
        return $app->server->serve();
    }
}
