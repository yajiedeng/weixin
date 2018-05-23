<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;

class MiniAppController extends Controller
{
    //小程序 客服接收消息&事件地址
    public function serve()
    {
        $app = app('wechat.mini_program');

        $app = app('wechat.official_account');

        $message = $app->server->getMessage();

        if(isset($message)){
            $openId = $message['FromUserName'];
            $text = new Text('hello');
            $app->customer_service->message($text)->to($openId)->send();
        }else{
            $app->server->push(function ($message) {
                // $message['FromUserName'] // 用户的 openid
                // $message['MsgType'] // 消息类型：event, text....
                return "hello";
            });
        }

        return $app->server->serve();





    }
}
