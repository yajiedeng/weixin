<?php

namespace App\Http\Controllers\Wechat;

use BaiduFace\Api\AipFace;
use Godruoyi\LaravelOCR\OCR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Text;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Log;
use Validator;

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
            $message = $app->server->getMessage();
            $openId = $message['FromUserName'];
            if($message['Content'] == "芝麻信用" || $message['Content'] == 1){
                //回复内容
                $zhima_url = config('wechat_parameter.zhima_url');
                $content = "你好，大道用车为你服务，绑定芝麻信用请点击链接:".$zhima_url;
                $text = new Text($content);
                $app->customer_service->message($text)->to($openId)->send();
            }else{
                $text = '';
                $app->customer_service->message($text)->to($openId)->send();
            }
        }elseif ($method == "GET") {
            $app->server->push(function ($message) {//首次token验证
                //回复内容
                $zhima_url = config('wechat_parameter.zhima_url');
                $content = "你好，大道用车为你服务，绑定芝麻信用请点击链接:".$zhima_url;
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
                if(!empty($result['unionid'])){
                    $user->unionid = $result['unionid'];
                }
                if($user->save()){
                    return responce(200,'success',$result['openid']);
                    die;
                }
            }elseif(!$user){
//                $user = User::create(['user_id'=>$user_id,'openid'=>$result['openid'],'session_key'=>$result['session_key'],'unionid'=>$result['unionid']]);
                $datas['user_id'] = $user_id;
                $datas['openid'] = $result['openid'];
                $datas['session_key'] = $result['session_key'];
                if(!empty($result['unionid'])){
                    $datas['unionid'] = $result['unionid'];
                }
                $user = User::create($datas);
                if($user){
                    return responce(200,'success',$result['openid']);
                    die;
                }
            }
        }
    }

    /*
     *  小程序验证身份证、驾驶证与真实姓名是否匹配
     *
     *  参数说明
     *  userName 姓名
     *  identityCardNum 身份证号码
     *  identityPositivePicture 身份证正面
     *  identityOppositePicture 身份证反面
     *  licensePicture 驾驶证正面
     *  licensePictureCopy 驾驶证反面
     *  selfie 自拍照
     * */
    public function userValidation()
    {
        //百度云图片前缀
        $baidu_bos_url = config('wechat_parameter.bcebos_url');
        //验证
        $error = Validator::make(request()->all(), [
                'userName' => 'required', // 姓名
                'identityCardNum' => 'required', // 身份证号码
                'identityPositivePicture' => 'required', // 身份证正面
                'identityOppositePicture' => 'required', //身份证反面
                'licensePicture' => 'required', // 驾驶证正面
                'licensePictureCopy' => 'required', // 驾驶证反面
                'selfie' => 'required', // 自拍照
            ],
            [
                'required' => ':attribute 该参数必填'
            ]
        );

        // 参数有错误 直接返回错误信息
        if($error->fails()){
            return responce(400,$error->errors()->first());
        }
        $data = request()->all();// 接收所以数据
        Log::info('验证用户证件参数 .'.json_encode($data));
        // 业务逻辑
        $userName = request('userName');
        $identityCardNum = request('identityCardNum');
        $identityPositivePicture = request('identityPositivePicture');
        $identityOppositePicture = request('identityOppositePicture');
        $licensePicture = request('licensePicture');
        $licensePictureCopy = request('licensePictureCopy');
        $selfie = request('selfie');

        // 获取身份证正面信息
        Log::info('身份证正面链接 '.$baidu_bos_url.$identityPositivePicture);
        $img = $baidu_bos_url.$identityPositivePicture;
        $identityPositivePictureInfo = OCR::baidu()->idcard($img,[
            'detect_direction'      => false,      //是否检测图像朝向
            'id_card_side'          => 'front',    //front：身份证正面；back：身份证背面 （注意，该参数必选）
            'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
        ]);
        if(!array_key_exists('error_code',$identityPositivePictureInfo)){
            $userNameCheck = $identityPositivePictureInfo['words_result']['姓名']['words'];
            $identityCardNumCheck = $identityPositivePictureInfo['words_result']['公民身份号码']['words'];
            if($userName != $userNameCheck || $identityCardNum != $identityCardNumCheck){
                return responce(400,'姓名或身份证号码不匹配');
            }
        }else{
            Log::error("身份证正面信息获取失败 === ".json_encode($identityPositivePictureInfo));
            return false;
        }

        // 获取身份证反面信息
        $img = $baidu_bos_url.$identityOppositePicture;
        $identityOppositePicturenfo = OCR::baidu()->idcard($img,[
            'detect_direction'      => false,      //是否检测图像朝向
            'id_card_side'          => 'back',    //front：身份证正面；back：身份证背面 （注意，该参数必选）
            'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
        ]);

        // 获取驾驶证信息
        $img = $baidu_bos_url.$licensePicture;
        $licensePictureInfo = OCR::baidu()->drivingLicense($img);
        if(!$licensePictureInfo){
            return responce(400,'驾驶证信息获取失败');
        }else{
            $userNameCheck = $licensePictureInfo['words_result']['姓名']['words'];
            $identityCardNumCheck = $licensePictureInfo['words_result']['证号']['words'];
            if($userName != $userNameCheck || $identityCardNum != $identityCardNumCheck){
                return responce(400,'姓名或驾驶证号码不匹配');
            }
        }

        $status = 1;

        // 人脸比对
        $images = [
            file_get_contents($baidu_bos_url.$identityPositivePicture),
            file_get_contents($baidu_bos_url.$selfie),
        ];

        $appId = config('ai.appId');
        $appKey = config('ai.apiKey');
        $appSecret = config('ai.apiSecret');
        $client = new AipFace($appId, $appKey, $appSecret);
        // 调用人脸检测
        $data = $client->match($images);
        if(!array_key_exists('error_code',$data)){
            $degree = $data['result'][0]['score']; // 人脸比对结果
        }else{
            Log::info("人脸对比失败 === ".json_encode($data));
            return false;
        }

        $data['degree'] = $degree;
        $data['status'] = $status;
        $data['identityPositiveInfoJsonStr'] = $identityPositivePictureInfo;
        $data['identityOppositeInfoJsonStr'] = $identityOppositePicturenfo;
        $data['licenseInfoJsonStr'] = $licensePictureInfo;

        return responce(200,"success",$data);
    }

    /*
     * 人脸比对
     * */
    public function faceMatch($images)
    {
        $appId = config('ai.appId');
        $appKey = config('ai.apiKey');
        $appSecret = config('ai.apiSecret');
        $client = new AipFace($appId, $appKey, $appSecret);
        // 调用人脸检测
        $data = $client->match($images);
        if(!array_key_exists('error_code',$data)){
            return $data['result'][0]['score'];
        }else{
            Log::info("人脸对比失败 === ".json_encode($data));
            return false;
        }
    }

    /*
     *  获取身份证图片信息内容
     * */
    public function getidentityCardNumInfo($img,$id_card_side = 'front')
    {
        $re = OCR::baidu()->idcard($img,[
            'detect_direction'      => false,      //是否检测图像朝向
            'id_card_side'          => $id_card_side,    //front：身份证正面；back：身份证背面 （注意，该参数必选）
            'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
        ]);
        if(!array_key_exists('error_code',$re)){
            return $re['words_result'];
        }else{
            Log::error("身份证正面信息获取失败 === ".json_encode($re));
            return false;
        }
    }

    /*
     *  获取驾驶证图片信息内容
     * */
    public function getlicensePictureInfo($img)
    {
        $re = OCR::baidu()->drivingLicense($img);
        if(!array_key_exists('error_code',$re)){
            return $re['words_result'];
        }else{
            Log::info("驾驶证信息获取失败 === ".json_encode($re));
            return false;
        }
    }

    /*
     *  小程序扫描查询车牌号
     * */
    public function getPlateNumber()
    {
        //获取参数
        $key = request('keywords','');
        Log::info('查询车牌号为的关键字是： '.$key);
        if(!empty($key)){
            //判断是链接还是 sence_id
            if(strpos($key,'http') > -1){
                $data = DB::table('car_qrcode')->where('url',$key)->first();
                $key = $data->secen_id;
            }
            $data = DB::table('car_plate_number')->where('secen_id',$key)->first();
            if($data){
                Log::info('车牌号是 '.$data->plate_number);
                $data = responce(200,'Gain success',$data->plate_number);
                return $data;
                die;
            }else{
                Log::error('没有找到车牌号');
                $data = responce(404,'No data information');
                return $data;
                die;
            }
        }else{
            $data = responce(400,'Error of parameters');
            return $data;
            die;
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
        $data = $wechat->createQrCode($secen_id);
        if($res){
            $time = date('m/d/Y H:i:s',time());
            $res = DB::table('car_qrcode')->insert(['url'=>$data['url'],'secen_id'=>$secen_id,'ticket'=>$data['ticket'],'status'=>1,'user_id'=>88888,'create_time'=>$time]);
        }
        var_dump($data);
        dd($res);
    }
}
