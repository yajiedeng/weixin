<?php

namespace App\Http\Controllers\Dadao;

use App\Http\Controllers\Wechat\WechatController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(Request $request){

        $current_url = getUrl();
        $reg_url = config('wechat_parameter.reg_url');
        $news = new \stdClass();
        $news->title = "新用户注册立即送";
        $news->description = "现在新用户注册就有大礼包相送，机会不等人，还不赶快来~";
        $news->url = $reg_url;
        $news->image = $current_url."/images/dadao.jpg";

        echo $news->title;

        dump($news);

        die;

        $re = DB::table('wx_message')->insert(['keyword'=>1,'content'=>$json,'type'=>2]);
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
