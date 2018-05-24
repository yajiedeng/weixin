<?php

namespace App\Http\Controllers\Dadao;

use App\Http\Controllers\Wechat\WechatController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends WechatController
{
    public function test(Request $request){
        $app = $this->service_app;
        dump($app);
        $url = getUrl();
        echo $url;

        echo "<br/>";

        echo "<img src='".$url."/images/dadao.jpg'>";




        $url = $request->url();
        echo $url;

        die;
        $re = $request->method();
        if($re == "POST"){
            return "this is post method";
        }elseif ($re == "GET") {
            return "this is get method";
        }
    }
}
