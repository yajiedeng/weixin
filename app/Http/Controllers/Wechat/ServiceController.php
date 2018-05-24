<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class ServiceController extends WechatController
{
    public $app;
    public function serve(Request $request)
    {
        $method = $request->method();
        $app = $this->service_app;

        if($method == "GET"){
            return $app->server->serve();
        }else{
            $this->responseMsg();
        }
    }

    public function responseMsg()
    {
        $app = $this->service_app;
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
        $Message = new MessageController();
        //判断事件类型
        if($message['MsgType'] == 'event'){//事件消息
            $Message->event();
        }elseif($message['MsgType'] == 'text'){//文本消息
            $message = new Text('Hello world!');
            $app->customer_service->message($message)->to($openId)->send();
            return $app->server->serve();
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