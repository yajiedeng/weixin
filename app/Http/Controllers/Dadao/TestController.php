<?php

namespace App\Http\Controllers\Dadao;


use App\Http\Controllers\Wechat\WechatController;
use App\Libs\Face;
use Godruoyi\LaravelOCR\OCR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Log;
use App\Models\User;
use BaiduFace\Api\AipFace;

class TestController extends Controller
{
    public function test(Request $request){

        $appId = config('ai.appId');
        $appKey = config('ai.apiKey');
        $appSecret = config('ai.apiSecret');
        $client = new AipFace($appId, $appKey, $appSecret);
        $image = file_get_contents(public_path().'/images/a.jpg');

        // 调用人脸检测
        $client->detect($image);

        die;

        $result = Ai::Nlp()->topic('标题','这里是测试文本');
        dump($result);


        die;

        $face = new Face();
        dd($face);

        die;

        $user = User::all();
        $user = User::find(28);
        dd($user);

        die;

        $re = $this->validate($request,[
            'name' => 'required|string|max:6',
        ]);

        dump($re);die;


        $res = $this->validate($request, [
            'name' => 'required'
        ]);
        dump($res);
        die;

        dump($request);

        die;

        $user = User::create(['name'=>'User 456']);
        $id = $user->id;
        echo $id;
        dump($user);
        die;

        $str = 'qrscene_db_42';
        $str = findNum($str);
        dump($str);
        die;

        // 身份证识别
        $img = "http://wechat.qihuapp.com/images/aaa.png";
        $filePath = $img;//"http://img2.imgtn.bdimg.com/it/u=3987992400,3053657058&fm=27&gp=0.jpg";//public_path().'/images/id_card.jpg';
        $re = OCR::baidu()->idcard($filePath,[
            'detect_direction'      => false,      //是否检测图像朝向
            'id_card_side'          => 'front',    //front：身份证正面；back：身份证背面 （注意，该参数必选）
            'detect_risk'           => false,      //是否开启身份证风险类型功能，默认false
        ]);
        return $re;

        die;

        $demo = new Demo();
        die;

        $wechat = new WechatController();
        dump($wechat);
        $result = $wechat->createQrCode('bd_15212');
        dump($result);
        die;

        $id = false;
        if($id){
            Log::info('this is id = '.$id);
        }else{
            Log::error('发生了错误信息');
        }
        die;

        $app = app('wechat.official_account');
        $list = $app->menu->list();
        dump($list);
        die;

        $content = DB::table('wx_message')->where('keyword',1)->first();
        $content = json_decode($content->content);
        dump($content);
        echo $content->title;
        die;

        $arr = [
            'title' => '新用户注册立即送',
            'description' => '现在新用户注册就有大礼包相送，机会不等人，还不赶快来~',
            'url' => 'http://mp.weixin.qq.com/s?__biz=MzUxMzQ2OTkzMw==&mid=100000006&idx=1&sn=1f42ba0c2e686475ef607ce034d75d57&chksm=7955fd844e2274926b5a541ebf4aa50912d16a22ae34cbdc8d975ae964a0ed37fb9dc20debd1&scene=18#wechat_redirect',
            'picurl' => 'https://mmbiz.qpic.cn/mmbiz_jpg/Fj8OO58Avd3p1NttnXIhoRuZHYFDicpS7O7ntgsTaVfYGZpOdolSWGvRth3UTWmbM54d16s3TLRYEM9VlmXnmtg/640?wx_fmt=jpeg&tp=webp&wxfrom=5&wx_lazy=1',
        ];

        $json = json_encode($arr);

        $re = DB::table('wx_message')->insert(['keyword'=>'subscribe','content'=>$json,'type'=>2]);
        die;


        $std = new \stdClass();
        $std->name = 'jack';
        $std->age = 18;
        $std->sex = '男';

        dump($std);
        die;

        $data = DB::table('wx_message')->where('keyword',16)->first();
        dump($data);
        die;
        if($data){
            echo $data->title;
        }else{
            echo 'not objec';
        }

        die;
        if($data){
            dump($data);
        }else{
            return "error";
        }



        die;
        $re = $request->method();
        if($re == "POST"){
            return "this is post method";
        }elseif ($re == "GET") {
            return "this is get method";
        }
    }
}
