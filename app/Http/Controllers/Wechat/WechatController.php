<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class WechatController extends Controller
{
    private $app;
    public function __construct()
    {
        //初始化
        $this->app = app('wechat.official_account');
    }

    public function serve(Request $request)
    {
        //请求方式
        $method = $request->method();
        if($method == "GET"){
            return $this->app->server->serve();
        }else{
            $this->responseMsg();
        }
    }

    /*
     * 接受用户消息
     * */
    private function responseMsg()
    {
        $msg = new MessageController();
        $message = $this->app->server->getMessage();
        //判断事件类型
        if($message['MsgType'] == 'event'){//事件消息
            $msg->event();
        }elseif($message['MsgType'] == 'text'){//文本消息
            $msg->responseKeyword(); //由于用户回复的文本消息 应该是走关键词回复
        }elseif($message['MsgType'] == 'image'){//图片消息

        }elseif($message['MsgType'] == 'voice'){//语音消息

        }elseif($message['MsgType'] == 'video'){//视频消息

        }elseif($message['MsgType'] == 'location'){//坐标消息

        }elseif($message['MsgType'] == 'file'){//文件消息

        }elseif($message['MsgType'] == 'link'){//链接消息

        }else{//其他消息

        }
    }

    /*
     * 生成二维码
     * @ $type 二维码类型 1是临时二维码 2是永久二维码 默认是2
     * @ $sence_id 二维码参数
     * */
    public function createQrCode($sence_id)
    {
        //生成永久二维码
        $result = $this->app->qrcode->forever($sence_id);
        return $result;//返回数组
    }

    /*
     * 获取二维码图片网址
     * */
    public function getQrCodeUrl($ticket)
    {
        $url = $this->app->qrcode->url($ticket);
        return $url;
    }
}