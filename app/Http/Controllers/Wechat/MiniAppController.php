<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;

class MiniAppController extends Controller
{
    private $method;
    private $app;
    public function __construct(Request $request)
    {
        //获取请求方式
        $this->method = $request->method();
        //初始化
        $this->app = app('wechat.mini_program');
    }

    //小程序 客服接收消息&事件地址
    public function serve(Request $request){
        $app = $this->app;
        //获取请求方式
        $method = $this->method;
        if($method == "POST"){//接收用户回复
            //回复内容
            $content = config('message.miniapp_zhima');
            $message = $app->server->getMessage();
            $openId = $message['FromUserName'];
            $text = new Text($content);
            $app->customer_service->message($text)->to($openId)->send();
        }elseif ($method == "GET") {
            $app->server->push(function ($message) {//首次token验证
                //回复内容
                $content = config('message.miniapp_zhima');
                // $message['FromUserName'] // 用户的 openid
                // $message['MsgType'] // 消息类型：event, text....
                return $content;
            });
        }
        return $app->server->serve();
    }

    //小程序用户获取openid
    public function getOpenid(Request $request){
        $method = $this->method;
        $app = $this->app;
        if($method != "POST"){
            responce('502','The server rejected your request');
        }

        //用户id
        $user_id = $request->input('user_id');
        //用户登陆code
        $code = $request->input('code');

        $res = $app->auth->session($code);
        dump($res);
    }
}
