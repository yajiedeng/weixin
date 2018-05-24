<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    private $method;
    private $app;
    public function __construct(Request $request)
    {
        //获取请求方式
        $this->method = $request->method();
        //初始化
        $this->app = app('wechat.official_account');
    }
    public function serve()
    {
        //请求方式
        $method = $this->method;
        $app = $this->app;
        if($method == "GET"){
            return $app->server->serve();
        }elseif($method == "POST"){
            $this->messgae();
        }else{
            return responce(404,'not found');
        }
    }

    public function message(){
        $message = $this->app->server->getMessage();
        //判断事件类型
        if($message['MsgType'] == 'event'){//事件消息
            $this->event();
        }elseif($message['MsgType'] == 'text'){//文本消息

        }elseif($message['MsgType'] == 'image'){//图片消息

        }elseif($message['MsgType'] == 'voice'){//语音消息

        }elseif($message['MsgType'] == 'video'){//视频消息

        }elseif($message['MsgType'] == 'location'){//坐标消息

        }elseif($message['MsgType'] == 'file'){//文件消息

        }elseif($message['MsgType'] == 'link'){//链接消息

        }else{//其他消息

        }
    }
}
