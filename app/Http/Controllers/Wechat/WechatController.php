<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class WechatController extends Controller
{
    public $app;
    public function __construct()
    {
        //初始化
        $this->app = app('wechat.official_account');
    }

    public function serve(Request $request)
    {
        //请求方式
        $method = $request->method();
        $app = $this->app;
        if($method == "GET"){
            return $app->server->serve();
        }else{
            $this->responseMsg();
        }
    }

    public function responseMsg()
    {
        $app = $this->app;
        $message = $app->server->getMessage();
        //判断事件类型
        if($message['MsgType'] == 'event'){//事件消息
            $this->event();
        }elseif($message['MsgType'] == 'text'){//文本消息
            $this->text();
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
     * 处理事件推送消息
     * */
    public function event()
    {
        $app = $this->app;
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
        $current_url = getUrl();
        $reg_url = config('wechat_parameter.reg_url');
        if($message['Event'] == 'subscribe'){
            $items = [
                new NewsItem([
                    'title'       => "新用户注册立即送",
                    'description' => '现在新用户注册就有大礼包相送，机会不等人，还不赶快来~',
                    'url'         => $reg_url,
                    'image'       => $current_url."/images/dadao.jpg",
                ]),
            ];
            $news = new News($items);
            $content = DB::table('wx_message')->where('keyword','subscribe')->first();
            if($content){
                $content = $content->content;
            }else{
                $content = '';
            }
            $message = new Text($content);
            $app->customer_service->message($message)->to($openId)->send();
            $app->customer_service->message($news)->to($openId)->send();
            return $app->server->serve();
        }
    }

    /*
     * 处理文本推送消息
     * */
    public function text()
    {
        $app = $this->app;
        $message = $app->server->getMessage();

        $keywords = $message['Content'];//接收关键字
        $content = DB::table('wx_message')->where('keyword',$keywords)->first();
        if($content){
            $content = $content->content;
        }else{
            $content = '';
        }
        $openId = $message['FromUserName'];
        $message = new Text($content);
        $app->customer_service->message($message)->to($openId)->send();
        return $app->server->serve();
    }
}