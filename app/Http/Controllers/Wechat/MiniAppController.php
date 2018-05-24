<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;

class MiniAppController extends Controller
{
    //小程序 客服接收消息&事件地址
    public function serve(Request $request)
    {
        //初始化
        $app = app('wechat.mini_program');
        if($request->isMethod('get')){
            $app->server->push(function ($message) {
                // $message['FromUserName'] // 用户的 openid
                // $message['MsgType'] // 消息类型：event, text....
                return "你好，大道用车为你服务，绑定芝麻信用请点击链接：https://wechat-oa.mydadao.com/zhima";
            });
        }elseif($request->isMethod('post')){
            $message = $app->server->getMessage();
            $openId = $message['FromUserName'];
            $text = new Text('你好，大道用车为你服务，绑定芝麻信用请点击链接：https://wechat-oa.mydadao.com/zhima');
            $app->customer_service->message($text)->to($openId)->send();
        }
        return $app->server->serve();
    }
}
