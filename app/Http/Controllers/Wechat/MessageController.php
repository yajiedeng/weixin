<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Qiyu\KefuController;
use App\Models\Message;
use EasyWeChat\Kernel\Messages\Raw;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use Illuminate\Support\Facades\DB;
use Log;
use Maatwebsite\Excel\Facades\Excel;


class MessageController extends BaseController
{
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
        $secenStr = $message['EventKey'];
        // 扫描渠道二维码
        if(strpos($secenStr,'bd_') > -1){
            // 存储参数二维码信息到数据库
            $secen_id = substr($secenStr,3,strlen($secenStr) -1);
            $dataOne = DB::table('qrcode')->where(['secen_id'=>$secenStr,'ticket'=>$message['Ticket']])->first();
            if(!$dataOne){
                $urlData = DB::table('channel')->where(['id'=>$secen_id])->first();
                $re = DB::table('channel')->where(['id'=>$secen_id])->update(['secen_id'=>$secenStr]);
                DB::table('qrcode_channel')->insert(['username'=>$urlData->username,'secen_id'=>$secen_id]);
                if($re){
                    $data = [
                        'secen_id' => $secen_id,
                        'ticket' => $message['Ticket'],
                        'url' => $urlData->url,
                        'codeType' => 2,
                        'typeStr' => 'bd',
                    ];
                    $re = DB::table('qrcode')->insert($data);
                    if($re){
                        Log::info("保存成功");
                    }else{
                        Log::error("保存失败");
                    }
                }
            }

            //回复注册的图文消息
            //注册链接拼接bd_id
            $reg_url = config('wechat_parameter.splicing_reg_url');
            $reg_url = sprintf($reg_url,$secen_id);
            $current_url = getUrl();
            $news = new \stdClass();
            $news->title = "新用户注册立即送";
            $news->description = "现在新用户注册就有大礼包相送，机会不等人，还不赶快来~";
            $news->url = $reg_url;
            $news->picurl = $current_url."/images/dadao.jpg";
            $this->responseNews($news);
            Log::info('用户扫描渠道二维码，渠道id:',['bd_id'=>$message['EventKey']]);
        }

        else{


            // 存储参数二维码信息到数据库
//            $secen_id = substr($secenStr,3,strlen($secenStr) -1);
            $dataOne = DB::table('car_qrcode')->where(['secen_id'=>$secenStr,'ticket'=>$message['Ticket']])->first();
            if(!$dataOne){
                DB::table('test')->insert(['name'=>"没有找到这个二维码"]);
                die;
            }
            DB::table('test')->insert(['name'=>"车辆二维码参数".$secenStr]);
            $file = public_path('excel/qrcode1.xlsx');
            //获取数组类型的数据
            $results = Excel::load($file)->get()->toArray();
            if(strpos($secenStr,'incar') > -1){
                $secenStr = substr($secenStr,5,strlen($secenStr)-1);
            }
            foreach ($results as $k => $v){

                if($v['secen'] == $secenStr){
                    DB::table('qrcode_car')->insert(['secen_id'=>$secenStr,'license_tag'=>$v['plate']]);
                }
            }
            $dataTwo = DB::table('qrcode')->where('secen_id',$secenStr)->first();
            if(!$dataTwo){
                $data = [
                    'secen_id' => $secenStr,
                    'ticket' => $message['Ticket'],
                    'url' => $dataOne->url,
                    'codeType' => 1,
                    'typeStr' => 'car',
                ];
                $re = DB::table('qrcode')->insert($data);
                if($re){
                    Log::info("保存成功");
                }else{
                    Log::error("保存失败");
                }
            }


            // 扫描车辆二维码 给用户推送小程序卡片
//            $car_secen_id = $secenStr;
            Log::info('用户扫描车辆二维码，车辆secen_id:',['secen_id'=>$secenStr]);
//            $this->raw($car_secen_id);
        }
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
        if($keywords == "人工客服"){
            $qiyu = new KefuController();
            $qiyu->serve();
        }
        $content = Message::where('keyword',$keywords)->first();
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
        $reg_url = config('wechat_parameter.reg_url');//注册链接
        $secenStr = $message['EventKey'];
        $openId = $message['FromUserName'];
        $user = new UserController();
        $user->getUserInfo($openId);
        if($secenStr){
            Log::info('用户扫描二维码关注',['event key'=>$secenStr]);
            //qrscene_db_42 实例数据
            if(strpos($secenStr,'bd_') > -1){//扫描渠道二维码进行关注
                $bd_id = substr($secenStr,11,strlen($secenStr) - 1);
                Log::info('用户扫描渠道二维码关注',['bd_id'=>$bd_id]);
                $reg_url = config('wechat_parameter.splicing_reg_url');//带参数的注册链接
                $reg_url = sprintf($reg_url,$bd_id);
            }
            else{//扫描车辆维码进行关注
                $car_secen_id = substr($secenStr,8,strlen($secenStr) - 1);
                Log::info('用户扫描车辆二维码关注',['car_secen_id'=>$car_secen_id]);
                // 给用户推送小程序卡片
                $this->raw($car_secen_id);
            }
        }

        //回复关注后的文本消息
        $content = Message::where('keyword','subscribe')->first();
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

    // 回复原始消息
    private function raw($param)
    {
        $appId = config('wechat_parameter.mini_appid');
        $msg = $this->app->server->getMessage();
        $openid = $msg['FromUserName'];
        $title = config('wechat_parameter.mini_title');
        $thumb_media_id = config('wechat_parameter.thumb_media_id');
        $path = 'pages/index/index?timeStr='.time().'&qrCode='.$param;
        $message = new Raw('{
            "touser":"'.$openid.'",
            "msgtype":"miniprogrampage",
            "miniprogrampage":
            {
                "title":"'.$title.'",
                "appid":"'.$appId.'",
                "pagepath":"'.$path.'",
                "thumb_media_id":"'.$thumb_media_id.'"
            }
        }');
        $this->app->customer_service->message($message)->to($openid)->send();
        return $this->app->server->serve();
    }
}
