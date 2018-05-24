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
        $app = $this->service_app;
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
     * 处理文本推送消息
     * */
    public function text()
    {
        $app = $this->service_app;
        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];
        $message = new Text('Hello world!');
        $app->customer_service->message($message)->to($openId)->send();
        return $app->server->serve();
    }
}