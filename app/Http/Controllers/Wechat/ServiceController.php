<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ServiceController extends WechatController
{
    public function serve(Request $request)
    {
        //请求方式
        $method = $request->method();
        $app = $this->service_app;
        $app->server->push(function($message){
            $Message = new MessageController();
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

//            return "欢迎关注 overtrue！";
        });
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