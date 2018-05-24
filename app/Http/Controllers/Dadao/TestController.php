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

        $data = DB::table('wx_message')->where('keyword',16)->first();
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
