<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;
use App\Models\User;
use App\Http\Controllers\Wechat\WechatController;
use Log;

class MiniAppController extends Controller
{
    private $method;
    private $app;
    public function __construct(Request $request)
    {
        //获取请求方式
        $this->method = $request->method();
        //初始化
        $this->app = app('wechat.mini_program');
    }

    //小程序 客服接收消息&事件地址
    public function serve(Request $request){
        $app = $this->app;
        //获取请求方式
        $method = $this->method;
        if($method == "POST"){//接收用户回复
            //回复内容
            $content = config('message.miniapp_zhima');
            $message = $app->server->getMessage();
            $openId = $message['FromUserName'];
            $text = new Text($content);
            $app->customer_service->message($text)->to($openId)->send();
        }elseif ($method == "GET") {
            $app->server->push(function ($message) {//首次token验证
                //回复内容
                $content = config('message.miniapp_zhima');
                // $message['FromUserName'] // 用户的 openid
                // $message['MsgType'] // 消息类型：event, text....
                return $content;
            });
        }
        return $app->server->serve();
    }

    //小程序用户获取openid
    public function getOpenid(Request $request){
        $method = $this->method;
        if($method != "POST"){
            return responce('502','The server rejected your request');
            die;
        }

        //接收参数
        $user_id = $request->input('user_id');
        $code = $request->input('code');
        $user = User::where('user_id', $user_id)->first();
        //查询是否有该用户信息
        if($code == -1){//获取openid
            if($user){//用户信息存在返回openid
                //记录返回 openid 日志
                Log::info('user '.$user_id.' get openid '.$user->openid);
                return responce(200,'success',$user->openid);
                die;
            }else{
                Log::info('user '.$user_id.' not openid ');
                return responce(400,'not openid','');
                die;
            }
        }else{//存储openid
            //获取用户openid unideid session_key
            $result = $this->app->auth->session($code);
            //记录获取 openid 日志
            Log::info('user '.$user_id.' wechat info '.json_encode($result));
            if(isset($result['errcode'])){
                return responce($result['errcode'],$result['errmsg']);
            }
            //判断该用户openid是否存在
            if($user && $user->openid != 0){//openid 已存在
                return responce(200,'success',$user->openid);
            }elseif ($user && $user->openid == 0){//上次存储失败 重新存储
                $user->user_id = $user_id;
                $user->openid = $result['openid'];
                $user->session_key = $result['session_key'];
//                $user->unionid = $result['unionid'];
                if($user->save()){
                    return responce(200,'success',$result['openid']);
                    die;
                }
            }elseif(!$user){
//                $user = User::create(['user_id'=>$user_id,'openid'=>$result['openid'],'session_key'=>$result['session_key'],'unionid'=>$result['unionid']]);
                $user = User::create(['user_id'=>$user_id,'openid'=>$result['openid'],'session_key'=>$result['session_key']]);
                if($user){
                    return responce(200,'success',$result['openid']);
                    die;
                }
            }
        }
    }

    /*
     *  小程序扫描查询车牌号
     * */
    public function getPlateNumber()
    {
        //获取参数
        $key = request('keywords');
        //判断是链接还是 sence_id
        if(strpos($key,'http') > -1){
            $data = DB::table('car_qrcode')->where('url',$key)->first();
            $key = $data->secen_id;
        }
        if(!empty($key)){
            $data = DB::table('car_plate_number')->where('secen_id',$key)->first();
            if($data){
                $data = responce(200,'Gain success',$data->plate_number);
                return $data;
                exit();
            }else{
                $data = responce(404,'No data information');
                return $data;
                exit();
            }
        }else{
            $data = responce(400,'Error of parameters');
            return $data;
        }
    }

    /*
     * 生成车辆二维码
     * */
    public function createCarQrCode()
    {
        $plate = request('plate','京Q5KK81');//$request->input('plate','京Q5KK81');
        $secen_id = time();
        $res = DB::table('car_plate_number')->insert(['plate_number'=>$plate,'secen_id'=>$secen_id]);
        //生成二维码
        $wechat = new WechatController;
        $result = $wechat->createQrCode($secen_id);
        //组装图片链接
        $imgUrl = $wechat->getQrCodeUrl($result['ticket']);
        $data = QrCodeController::createQrCode("cart",$secen_id);
        if($res){
            $time = date('m/d/Y H:i:s',time());
            $res = DB::table('car_qrcode')->insert(['url'=>$data['url'],'secen_id'=>$secen_id,'ticket'=>$data['ticket'],'status'=>1,'user_id'=>88888,'create_time'=>$time]);
        }
        var_dump($data);
        dd($res);
        $data = QrCodeController::createQrCode("cart",'11111111');
        dd($data);
    }
}
