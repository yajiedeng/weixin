<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;


class MessageController extends Controller
{
    private $app;
    private $message;
    private $current_url;

    public function __construct(Request $request)
    {
        //初始化
        $this->app = app('wechat.official_account');
        $this->message = $this->app->server->getMessage();
        $this->current_url = getUrl();
    }
    /*
     * 事件消息处理
     * */
    public function event()
    {
        $message = $this->message();
        if($message['Event'] == 'subscribe'){//关注事件
            $this->subscribe();
        }elseif($message['Event'] == 'CLICK'){//点击自定义菜单事件

        }elseif($message['Event'] == 'SCAN'){//扫描二维码事件

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
        return $this->app->server->serve();
    }
}
