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

       $this->responseNews($news);

        die;

        $re = DB::table('wx_message')->insert(['keyword'=>1,'content'=>$json,'type'=>2]);
        die;
    }
    public function responseNews($news)
    {
        echo $news->title;
        dump($news);
    }
}
