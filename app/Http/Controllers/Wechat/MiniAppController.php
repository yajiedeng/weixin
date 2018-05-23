<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MiniAppController extends Controller
{
    //小程序 客服接收消息&事件地址
    public function serve()
    {
        $app = app('wechat.mini_program');
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
//        $service = $app->customer_service;
        $text = new Text('hello');
        return $app->customer_service->message($text)->to($openId)->send();
        return $app->server->serve();
    }
}
