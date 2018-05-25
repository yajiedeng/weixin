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
        $current_url = getUrl();
        $reg_url = config('wechat_parameter.reg_url');
        if($message['Event'] == 'subscribe'){//关注事件
            //回复关注后的文本消息
            $content = DB::table('wx_message')->where('keyword','subscribe')->first();
            $content = $content == null ? "" : $content->content;
            $this->resposeText($content);
            //回复一条图文消息
            $news = new \stdClass();
            $news->title = "新用户注册立即送";
            $news->description = "现在新用户注册就有大礼包相送，机会不等人，还不赶快来~";
            $news->url = $reg_url;
            $news->picurl = $current_url."/images/dadao.jpg";
            $this->responseNews($news);
        }
    }

    /*
     * 处理关键字回复
     * */
    public function text()
    {
        $app = $this->app;
        $msg = $app->server->getMessage();
        $keywords = $msg['Content'];//接收关键字
        $content = DB::table('wx_message')->where('keyword',$keywords)->first();
        if($content){
            //判断关键字回复类型
            if($content->type == 1){//文本消息
                $content = $content->content;
                $this->resposeText($content);
            }elseif($content->type == 2){//图文消息
                $content = json_decode($content->content);
                $this->responseNews($content);
            }
        }else{
            $content = '';
            $this->resposeText($content);
        }
    }

    /*
     * 文本消息回复
     * */
    public function resposeText($content)
    {
        $app = $this->app;
        $msg = $app->server->getMessage();
        $openId = $msg['FromUserName'];
        $message = new Text($content);
        $app->customer_service->message($message)->to($openId)->send();
        return $app->server->serve();
    }

    /*
     * 回复图文消息
     * */
    public function responseNews($news)
    {
        $app = $this->app;
        $msg = $app->server->getMessage();
        $openId = $msg['FromUserName'];
        $items = [
            new NewsItem([
                'title'       => $news->title,
                'description' => $news->description,
                'url'         => $news->url,
                'image'       => $news->picurl,
            ]),
        ];
        $news = new News($items);
        $app->customer_service->message($news)->to($openId)->send();
        return $app->server->serve();
    }
}