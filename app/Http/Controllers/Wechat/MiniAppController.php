<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;
use App\Models\User;
use Log;

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
        if($method != "POST"){
            responce('502','The server rejected your request');
            die;
        }

        //接收参数
        $user_id = $request->input('user_id');
        $code = $request->input('code');
        $user = User::find($user_id);
        //获取用户openid unideid session_key
        $result = $this->app->auth->session($code);
        Log::info('user '.$user_id.' wechat info '.json_encode($result));
        if($result['errcode']){
            responce(400,'invalid code','');
            die;
        }
        //查询是否有该用户信息
        if($code == -1){//获取openid
            if($user){//用户信息存在返回openid
                responce(200,'success',$user->openid);
                die;
            }else{
                responce(400,'not openid','');
                die;
            }
        }else{//存储openid
            //判断该用户openid是否存在
            if($user && $user->openid != 0){//openid 已存在
                responce(200,'success',$user->openid);
            }elseif ($user && $user->openid == 0){//上次存储失败 重新存储
                $user->user_id = $user_id;
                $user->openid = $result['openid'];
                $user->session_key = $result['session_key'];
//                $user->unionid = $result['unionid'];
                if($user->save()){
                    responce(200,'success',$result['openid']);
                    die;
                }
            }elseif(!$user){
//                $user = User::create(['user_id'=>$user_id,'openid'=>$result['openid'],'session_key'=>$result['session_key'],'unionid'=>$result['unionid']]);
                $user = User::create(['user_id'=>$user_id,'openid'=>$result['openid'],'session_key'=>$result['session_key']]);
                if($user){
                    responce(200,'success',$result['openid']);
                    die;
                }
            }
        }
    }
}
