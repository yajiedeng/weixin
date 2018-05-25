<?php

namespace App\Http\Controllers\Dadao;

use App\Http\Controllers\Wechat\WechatController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Log;

class TestController extends Controller
{
    public function test(Request $request){

        $wechat = new WechatController();
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
