<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use Illuminate\Support\Facades\DB;
use Log;


class MessageController extends Controller
{
    private $app;
    public function __construct()
    {
        //初始化
        $this->app = app('wechat.official_account');
    }

    /*
     * 处理事件推送消息
     * */
    public function event()
    {
        $message = $this->app->server->getMessage();
        if($message['Event'] == 'subscribe'){//关注事件
            $this->subscribe();
        }elseif (strtolower($message['Event']) == 'click'){//自定义菜单点击事件
            $this->responseClick();
        }elseif (strtolower($message['Event']) == "scan"){//已关注用户扫码
            $this->responseScan();
        }
    }

    /*
     * 扫码事件处理
     * */
    private function responseScan()
    {
        $message = $this->app->server->getMessage();
        $senceStr = $message['EventKey'];
        if(strpos($senceStr,'bd_') > -1){//扫描渠道二维码
            //回复注册的图文消息
            $reg_url = config('wechat_parameter.splicing_reg_url');//注册链接拼接bd_id
            $current_url = getUrl();
            $news = new \stdClass();
            $news->title = "新用户注册立即送";
            $news->description = "现在新用户注册就有大礼包相送，机会不等人，还不赶快来~";
            $news->url = $reg_url;
            $news->picurl = $current_url."/images/dadao.jpg";
            $this->responseNews($news);
        }
        Log::info('用户扫码',['key'=>$message['EventKey']]);

    }

    /*
     * 自定义菜单点击事件
     * */
    private function responseClick()
    {
        $message = $this->app->server->getMessage();
        $keywords = $message['EventKey'];//接收关键字
        $this->responseKeyword($keywords);
    }

    /*
     * 处理关键字回复
     * */
    public function responseKeyword($keywords= '')
    {
        if(empty($keywords)){
            $msg = $this->app->server->getMessage();
            $keywords = $msg['Content'];//接收关键字
        }
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
     * 关注事件后处理
     * */
    private function subscribe()
    {
        $message = $this->app->server->getMessage();
        if($message['EventKey']){
            Log::info('用户扫码',['key'=>$message['EventKey']]);
            if(strpos($message['EventKey'],'bd') > -1){//扫描渠道二维码进行关注
                $reg_url = config('wechat_parameter.splicing_reg_url');
            }else{//扫描车辆维码进行关注

            }
        }else{
            $reg_url = config('wechat_parameter.reg_url');
        }

        //回复关注后的文本消息
        $content = DB::table('wx_message')->where('keyword','subscribe')->first();
        $content = $content == null ? "" : $content->content;
        $this->resposeText($content);
        //回复一条图文消息
        $current_url = getUrl();
        $news = new \stdClass();
        $news->title = "新用户注册立即送";
        $news->description = "现在新用户注册就有大礼包相送，机会不等人，还不赶快来~";
        $news->url = $reg_url;
        $news->picurl = $current_url."/images/dadao.jpg";
        $this->responseNews($news);
    }

    /*
     * 文本消息回复
     * */
    private function resposeText($content)
    {
        $msg = $this->app->server->getMessage();
        $openId = $msg['FromUserName'];
        $message = new Text($content);
        $this->app->customer_service->message($message)->to($openId)->send();
        return $this->app->server->serve();
    }

    /*
     * 回复图文消息
     * */
    private function responseNews($news)
    {
        $msg = $this->app->server->getMessage();
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
        $this->app->customer_service->message($news)->to($openId)->send();
        return $this->app->server->serve();
    }
}
