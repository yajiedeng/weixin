<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;


class MessageController extends WechatController
{
    /*
     * 处理事件推送消息
     * */
    public static function event()
    {
        $app = app('wechat.official_account');
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
            $content = "亲爱的“稻米”，终于等到你！
燃油车大众polo上线，芝麻信用免押金租车。
详情请点击：马上用车—免押金及费用 查看

回复以下数字，还可get其它相关内容：  
【1】收费标准 
【2】还车区域 
【3】产生违章 
【4】物品遗失 
【5】事故处理  
【6】免除押金

百里加急客服电话：400-616-6161

 ";
            $message = new Text($content);
            $app->customer_service->message($message)->to($openId)->send();
            $app->customer_service->message($news)->to($openId)->send();
            return $app->server->serve();
        }
    }

    /*
     * 关注事件后处理
     * */
    public function subscribe()
    {
        $openId = $this->message['FromUserName'];
        $items = [
            new NewsItem([
                'title'       => "新用户注册立即送",
                'description' => '现在新用户注册就有大礼包相送，机会不等人，还不赶快来~',
                'url'         => $this->current_url."/images/dadao.jpg",
                'image'       => $this->current_url."/images/dadao.jpg",
            ]),
        ];
        $news = new News($items);
        $this->app->customer_service->message($news)->to($openId)->send();
    }
}
