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
        $app = $this->service_app;

        $message = $app->server->getMessage();
        $openId = $message['FromUserName'];

        //判断事件类型
        if($message['MsgType'] == 'event'){//事件消息
            if($message['Event'] == 'subscribe'){
                $items = [
                    new NewsItem([
                        'title'       => "新用户注册立即送",
                        'description' => '现在新用户注册就有大礼包相送，机会不等人，还不赶快来~',
                        'url'         => $this->current_url."/images/dadao.jpg",
                        'image'       => $this->current_url."/images/dadao.jpg",
                    ]),
                ];
                $news = new News($items);
                $app->customer_service->message($news)->to($openId)->send();
                return $app->server->serve();
            }
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
        return $app->server->serve();
    }

    public function message(){
        $app = $this->service_app;
        $message = $app->server->getMessage();
        $Message = new MessageController;
        //判断事件类型
        if($message['MsgType'] == 'event'){//事件消息
            $Message->event();
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